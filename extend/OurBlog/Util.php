<?php

class OurBlog_Util
{
    public function generateRegToken($uid)
    {
        return md5('OurBlog-User-Reg-Token-' . $uid . '-' . microtime());
    }
}
