<?php

namespace app\controller\admin;

use app\BaseController;

use think\facade\Session;
use think\facade\View;
use think\facade\Db;

class Post extends BaseController
{
    protected $middleware = array(
        \app\middleware\Auth::class
    );

    public function add()
    {
        $error = '';
        if ($_POST) {
            try {
                $post = new \OurBlog_Post(Session::get('id'));
                $post->add($_POST);
                return redirect('/admin.index');
            } catch (\InvalidArgumentException $e) {
                $error = $e->getMessage();
            } catch (\Exception $e) {
                $error = 'SERVER ERROR';
            }
        }

        View::assign('error', $error);
        return View::fetch();
    }

    public function edit()
    {
        $error = '';
        if ($_POST) {
            try {
                $post = new \OurBlog_Post(Session::get('id'));
                $post->edit($_POST);
                return redirect('/admin.index');
            } catch (\InvalidArgumentException $e) {
                $error = $e->getMessage();
            } catch (\Exception $e) {
                $error = 'SERVER ERROR';
            }
        }

        try {
            if (!isset($_GET['id'])) {
                throw new \InvalidArgumentException('missing required key id');
            }
            $id = \OurBlog_Post::DBAIPK($_GET['id']);
            if (!$id) {
                throw new \InvalidArgumentException('invalid id');
            }
            $postRow = Db::table('post')
                        ->field('category_id,title,is_external,content')
                        ->where('id', $id)
                        ->where('user_id', Session::get('id'))
                        ->find();
            if (!$postRow) {
                throw new \InvalidArgumentException('id not exists or not your post');
            }
        } catch (\InvalidArgumentException $e) {
            return redirect('/admin.index');
        }

        View::assign(array(
            'error'   => $error,
            'id'      => $id,
            'postRow' => $postRow
        ));
        return View::fetch();
    }

    public function delete()
    {
        try {
            $post = new \OurBlog_Post(Session::get('id'));
            $post->delete($_GET);
            return redirect('/admin.index');
        } catch (\InvalidArgumentException $e) {
            return $e->getMessage();
        } catch (\Exception $e) {
            return 'SERVER ERROR';
        }
    }
}
