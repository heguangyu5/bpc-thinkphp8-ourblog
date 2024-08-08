<?php

namespace app\controller\admin;

use app\BaseController;

use think\facade\Session;

class Logout extends BaseController
{
    protected $middleware = array(
        \app\middleware\Auth::class
    );

    public function index()
    {
        Session::destroy();
        return redirect('/admin.login');
    }
}
