<?php

namespace app\controller;

use app\BaseController;

class Activate extends BaseController
{
    public function index()
    {
        try {
            \OurBlog_User::activate($_GET);
            return redirect('/admin/login?activate=success');
        } catch (\InvalidArgumentException $e) {
            return $e->getMessage();
        } catch (\Exception $e) {
            return 'SERVER ERROR';
        }
    }
}
