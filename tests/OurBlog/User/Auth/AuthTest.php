<?php

class OurBlog_User_AuthTest extends OurBlog_DatabaseTestCase
{
    static $classGroups = 'auth';

    protected $data;

    public function getDataSet()
    {
        $this->data = array(
            'email'    => 'bob@ourats.com',
            'password' => '123456',
        );

        return $this->createArrayDataSet(array());
    }

    public function testEmailKeyIsRequired()
    {
        unset($this->data['email']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('missing required key email');

        OurBlog_User::auth($this->data);
    }

    public function testEmailMinLength()
    {
        $this->data['email'] = 'a@bc';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid email, length limit 5 ~ 200');

        OurBlog_User::auth($this->data);
    }

    public function testEmailMaxLength()
    {
        $this->data['email'] = str_pad('bob@ourats.com', 201, 'a', STR_PAD_LEFT);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid email, length limit 5 ~ 200');

        OurBlog_User::auth($this->data);
    }

    public function dataProviderTestEmailFormat()
    {
        return array(
            array('Iambob'),
            array('<script>alert("bob")</script>@qq.com'),
            array('bob@####'),
            array('bob@ourats')
        );
    }

    public function testEmailFormat($email)
    {
        $this->data['email'] = $email;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid email');

        OurBlog_User::auth($this->data);
    }

    public function testPasswordKeyIsRequired()
    {
        unset($this->data['password']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('missing required key password');

        OurBlog_User::auth($this->data);
    }

    public function testPasswordMinLength()
    {
        $this->data['password'] = '12345';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid password, length limit 6 ~ 50');

        OurBlog_User::auth($this->data);
    }

    public function testPasswordMaxLength()
    {
        $this->data['password'] = str_pad('123456', 51, 'a');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid password, length limit 6 ~ 50');

        OurBlog_User::auth($this->data);
    }

    public function dataProviderTestWrongEmailPasswords()
    {
        return array(
            array(array('email' => 'bob@ourats.com', 'password' => '1234567')),
            array(array('email' => 'bob2@ourats.com', 'password' => '123456')),
            array(array('email' => 'b@ourats.com', 'password' => '1234567'))
        );
    }

    public function testWrongEmailPasswords($data)
    {
        $this->assertFalse(OurBlog_User::auth($data));
    }

    public function testUnactivatedUser()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('please activate your account first!');

        OurBlog_User::auth(array(
            'email'    => 'jim@ourats.com',
            'password' => '6543210'
        ));
    }

    public function testAuth()
    {
        $this->assertEquals(
            array('id' => 1, 'username' => 'Bob'),
            OurBlog_User::auth($this->data)
        );
    }
}
