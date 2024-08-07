<?php

namespace app\controller;

use app\BaseController;

use think\facade\View;

class Index extends BaseController
{
    public function index()
    {
        return View::fetch();
    }

    public function hello($name = 'ThinkPHP8')
    {
        return 'hello,' . $name;
    }
}
