<?php

use think\facade\Db;

class OurBlog_Post
{
    protected $uid;

    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    // DBAIPK short for DB Auto Increment Primary Key
    public static function DBAIPK($var)
    {
        return filter_var($var, FILTER_VALIDATE_INT, array(
            'options' => array('min_range' => 1)
        ));
    }

    protected function preparePostData(array $data)
    {
        $requiredKeys = array(
            // key => required
            'categoryId' => true,
            'title'      => true,
            'content'    => false,
            'tags'       => false
        );
        foreach ($requiredKeys as $key => $required) {
            if (!isset($data[$key])) {
                throw new InvalidArgumentException("missing required key $key");
            }
            $data[$key] = trim($data[$key]);
            if ($required && $data[$key] == '') {
                throw new InvalidArgumentException("$key required");
            }
        }
        // categoryId
        $data['categoryId'] = self::DBAIPK($data['categoryId']);
        if (!$data['categoryId']) {
            throw new InvalidArgumentException('invalid categoryId');
        }
        // title
        $len = mb_strlen($data['title'], 'UTF-8');
        if ($len > 500) {
            throw new InvalidArgumentException('title too long, maxlength is 500');
        }
        // content
        $len = strlen($data['content']);
        if (isset($data['external']) && $data['external'] == '1') {
            if ($len > 1000) {
                throw new InvalidArgumentException('external url too long');
            }
            if (!preg_match('#^https?://[^"]+$#', $data['content'])) {
                throw new InvalidArgumentException('invalid external url');
            }
        } else {
            if ($len > 64000) {
                throw new InvalidArgumentException('content too long, maxlength is 64000 bytes');
            }
            $data['external'] = 0;
        }
        // tags
        if ($data['tags']) {
            $len = mb_strlen($data['tags']);
            if ($len > 400) {
                throw new InvalidArgumentException('tags too long');
            }
            $tags = explode(',', $data['tags']);
            if (count($tags) > 10) {
                throw new InvalidArgumentException('too many tags');
            }
            $tagIdMap = array();
            foreach ($tags as $idx => $tag) {
                $tag = trim($tag);
                $len = mb_strlen($tag, 'UTF-8');
                if ($len > 30) {
                    throw new InvalidArgumentException('tag too long');
                }
                if ($len == 0) {
                    continue;
                }
                $tagIdMap[$tag] = 0;
            }
            unset($data['tags']);
            if ($tagIdMap) {
                // filter out exist tags
                $tagRows = Db::table('tag')
                            ->field('id,name')
                            ->whereIn('name', array_keys($tagIdMap))
                            ->select();
                foreach ($tagRows as $row) {
                    $tagIdMap[$row['name']] = $row['id'];
                }
                $data['tagIdMap'] = $tagIdMap;
                // filter out new tags
                $newTags = array();
                foreach ($tagIdMap as $tag => $tagId) {
                    if (!$tagId) {
                        $newTags[] = $tag;
                    }
                }
                $data['newTags'] = $newTags;
            }
        }

        return $data;
    }

    public function add(array $data)
    {
        $data = $this->preparePostData($data);

        Db::transaction(function () use ($data) {
            // post
            $postId = Db::table('post')->insertGetId(array(
                'category_id' => $data['categoryId'],
                'title'       => $data['title'],
                'is_external' => $data['external'],
                'content'     => $data['content'],
                'user_id'     => $this->uid
            ));
            // tags
            if (isset($data['tagIdMap'])) {
                // tag
                foreach ($data['newTags'] as $tag) {
                    $data['tagIdMap'][$tag] = Db::table('tag')->insertGetId(array(
                        'name' => $tag
                    ));
                }
                // post_tag
                foreach ($data['tagIdMap'] as $tagId) {
                    Db::table('post_tag')->insert(array(
                        'post_id' => $postId,
                        'tag_id'  => $tagId
                    ));
                }
            }
        });
    }

    protected function preparePostId(array $data)
    {
        if (!isset($data['id'])) {
            throw new InvalidArgumentException('missing required key id');
        }
        $data['id'] = self::DBAIPK($data['id']);
        if (!$data['id']) {
            throw new InvalidArgumentException('invalid id');
        }
        if (!Db::table('post')->where('id', $data['id'])->where('user_id', $this->uid)->value('id')) {
            throw new InvalidArgumentException('id not exists or not your post');
        }

        return $data;
    }

    public function edit(array $data)
    {
        $data = self::preparePostData($data);
        $data = self::preparePostId($data);

        if (isset($data['tagIdMap'])) {
            // get post tags from db
            $postTagIds = Db::table('post_tag')->where('post_id', $data['id'])->column('tag_id');
            // diff
            $tagIds = array();
            foreach ($data['tagIdMap'] as $tagId) {
                if ($tagId) {
                    $tagIds[] = $tagId;
                }
            }
            $tagIdsToBeAdded   = array_diff($tagIds, $postTagIds);
            $tagIdsToBeDeleted = array_diff($postTagIds, $tagIds);
        }

        Db::startTrans();
        try {
            // post
            Db::table('post')->update(array(
                'id'          => $data['id'],
                'category_id' => $data['categoryId'],
                'title'       => $data['title'],
                'is_external' => $data['external'],
                'content'     => $data['content'],
                'update_date' => date('Y-m-d H:i:s')
            ));
            // tags
            if (isset($data['tagIdMap'])) {
                // newTags
                if ($data['newTags']) {
                    foreach ($data['newTags'] as $tag) {
                        $tagId = Db::table('tag')->insertGetId(array(
                            'name' => $tag
                        ));
                        Db::table('post_tag')->insert(array(
                            'post_id' => $data['id'],
                            'tag_id'  => $tagId
                        ));
                    }
                }
                // toBeAdded
                if ($tagIdsToBeAdded) {
                    foreach ($tagIdsToBeAdded as $tagId) {
                        Db::table('post_tag')->insert(array(
                            'post_id' => $data['id'],
                            'tag_id'  => $tagId
                        ));
                    }
                }
                // toBeDeleted
                if ($tagIdsToBeDeleted) {
                    Db::table('post_tag')
                        ->where('post_id', $data['id'])
                        ->whereIn('tag_id', $tagIdsToBeDeleted)
                        ->delete();
                }
            } else {
                Db::table('post_tag')->where('post_id', $data['id'])->delete();
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    public function delete(array $data)
    {
        $data = $this->preparePostId($data);
        $postId = $data['id'];

        Db::transaction(function () use ($postId) {
            Db::table('post')->delete($postId);
            Db::table('post_tag')->where('post_id', $postId)->delete();
        });
    }
}
