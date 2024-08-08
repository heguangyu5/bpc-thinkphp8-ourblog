<?php

namespace app\controller;

use app\BaseController;

use think\facade\View;

class Reg extends BaseController
{
    public function index()
    {
        $error = '';
        if ($_POST) {
            try {
                \OurBlog_User::reg($_POST, new \OurBlog_Util());
                return "reg success! we have sent you an activate email, please click the link in the email to activate your account.";
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
