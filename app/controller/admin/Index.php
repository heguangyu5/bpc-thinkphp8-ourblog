<?php

namespace app\controller\admin;

use app\BaseController;

use think\facade\View;

class Index extends BaseController
{
    protected $middleware = array(
        \think\middleware\SessionInit::class,
        \app\middleware\Auth::class
    );

    public function index()
    {
        return View::fetch();
    }
}
