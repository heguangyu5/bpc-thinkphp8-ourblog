<?php

namespace app\controller;

use app\BaseController;

use think\facade\View;
use think\facade\Db;

class Post extends BaseController
{
    public function index()
    {
        try {
            if (!isset($_GET['id'])) {
                throw new InvalidArgumentException('missing required key id');
            }
            $id = \OurBlog_Post::DBAIPK($_GET['id']);
            if (!$id) {
                throw new \InvalidArgumentException('invalid id');
            }
            $sql = "SELECT  p.title,
                            p.is_external,
                            p.content,
                            p.create_date,
                            u.username
                    FROM    post p
                            INNER JOIN user u ON p.user_id = u.id
                    WHERE
                            p.id = $id";
            $post = Db::query($sql);
            if (!$post) {
                throw new \InvalidArgumentException('id not exists');
            }
            $post = $post[0];
            if ($post['is_external']) {
                return redirect($post['content']);
            }
        } catch (\InvalidArgumentException $e) {
            return redirect('/');
        }

        View::assign('post', $post);
        return View::fetch();
    }
}
