<?php

namespace app\controller\admin;

use app\BaseController;

use think\facade\Session;
use think\facade\View;

class Login extends BaseController
{
    protected $middleware = array(\think\middleware\SessionInit::class);

    public function index()
    {
        if (Session::has('id')) {
            return redirect('/admin.index');
        }

        $error = '';
        if ($_POST) {
            try {
                $userInfo = \OurBlog_User::auth($_POST);
                if ($userInfo) {
                    Session::regenerate(true);
                    Session::set('id',       $userInfo['id']);
                    Session::set('username', $userInfo['username']);
                    return redirect('/admin.index');
                }
                $error = 'Email or Password wrong!';
            } catch (\InvalidArgumentException $e) {
                $error = $e->getMessage();
            } catch (\Exception $e) {
                $error = 'SERVER ERROR';
            }
        }

        View::assign('error', $error);
        return View::fetch();
    }
}
