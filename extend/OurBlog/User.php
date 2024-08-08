<?php

use think\facade\Db;

class OurBlog_User
{
    protected static function prepareRegData(array $data)
    {
        $requiredKeys = array('email', 'username', 'password', 'confirmPassword');
        foreach ($requiredKeys as $key) {
            if (!isset($data[$key])) {
                throw new InvalidArgumentException("missing required key $key");
            }
            $data[$key] = trim($data[$key]);
            if (!$data[$key]) {
                throw new InvalidArgumentException("$key required");
            }
        }

        // email
        $len = strlen($data['email']);
        if ($len < 5) {
            throw new InvalidArgumentException('email too short, minlength is 5');
        }
        if ($len > 200) {
            throw new InvalidArgumentException('email too long, maxlength is 200');
        }
        $data['email'] = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        if (!$data['email']) {
            throw new InvalidArgumentException('invalid email');
        }
        // username
        if (mb_strlen($data['username'], 'UTF-8') > 30) {
            throw new InvalidArgumentException('username too long, maxlength is 30');
        }
        // password
        $len = strlen($data['password']);
        if ($len < 6 || $len > 50) {
            throw new InvalidArgumentException('invalid password, length limit 6 ~ 50');
        }
        // confirmPassword
        if ($data['confirmPassword'] != $data['password']) {
            throw new InvalidArgumentException('confirmPassword should equal to password');
        }
        // is email already registered?
        if (Db::table('user')->where('email', $data['email'])->value('id')) {
            throw new InvalidArgumentException('email already registered');
        }

        return $data;
    }

    public static function reg(array $data, OurBlog_Util $util)
    {
        $data = self::prepareRegData($data);

        Db::transaction(function () use ($data, $util) {
            // user
            $uid = Db::table('user')->insertGetId(array(
                'email'    => $data['email'],
                'username' => $data['username'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT)
            ));
            // user.reg_token
            $regToken = $util->generateRegToken($uid);
            Db::table('user')->update(array(
                'id'        => $uid,
                'reg_token' => $regToken
            ));
            // mail_queue
            Db::table('mail_queue')->insert(array(
                'to'      => $data['email'],
                'subject' => 'OurBlog: Please activate your account',
                'body'    => "Hello {$data['username']}, Welcome to OurBlog.

Please activate your account by click the link below:

    http://localhost/ourblog/activate.php?id=$uid&token=$regToken

Thanks."
            ));
        });
    }

    public static function activate(array $data)
    {
        // id
        if (!isset($data['id'])) {
            throw new InvalidArgumentException('missing required key id');
        }
        $id = OurBlog_Post::DBAIPK($data['id']);
        if (!$id) {
            throw new InvalidArgumentException('invalid id');
        }
        // token
        if (!isset($data['token'])) {
            throw new InvalidArgumentException('missing required key token');
        }
        $len = strlen($data['token']);
        if ($len != 32) {
            throw new InvalidArgumentException('invalid token');
        }

        if (!Db::table('user')->where('id', $id)->where('reg_token', $data['token'])->value('id')) {
            throw new InvalidArgumentException('token not exists, have you activated before?');
        }

        Db::table('user')->update(array(
            'id'          => $id,
            'reg_token'   => null,
            'update_date' => date('Y-m-d H:i:s')
        ));
    }

    public static function auth(array $data)
    {
        // email
        if (!isset($data['email'])) {
            throw new InvalidArgumentException('missing required key email');
        }
        $len = strlen($data['email']);
        if ($len < 5 || $len > 200) {
            throw new InvalidArgumentException('invalid email, length limit 5 ~ 200');
        }
        $data['email'] = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        if (!$data['email']) {
            throw new InvalidArgumentException('invalid email');
        }
        // password
        if (!isset($data['password'])) {
            throw new InvalidArgumentException('missing required key password');
        }
        $len = strlen($data['password']);
        if ($len < 6 || $len > 50) {
            throw new InvalidArgumentException('invalid password, length limit 6 ~ 50');
        }

        $row = Db::table('user')
                ->where('email', $data['email'])
                ->field('id,username,reg_token,password')
                ->find();
        if (!$row || !password_verify($data['password'], $row['password'])) {
            return false;
        }
        if ($row['reg_token']) {
            throw new InvalidArgumentException('please activate your account first!');
        }
        unset($row['reg_token'], $row['password']);
        return $row;
    }
}
