<?php

require "think-orm-stubs/load_stubs.php";

spl_autoload_register(function ($class) {
    if (strncmp($class, 'think\\', 6) === 0) {
        $includePath = '/usr/share/php';
        $thinkDirs   = array('think-orm', 'think-helper');
        $file        = str_replace('\\', '/', $class) . '.php';
        foreach ($thinkDirs as $dir) {
            $path = "$includePath/$dir/$file";
            if (file_exists($path)) {
                require $path;
                return;
            }
        }
    } else {
        require str_replace('_', '/', $class) . '.php';
    }
});

\think\facade\Db::setConfig(array(
    'default'     => 'mysql',
    'connections' => array(
        'mysql' => array(
            'type'     => 'mysql',
            'hostname' => '127.0.0.1',
            'hostport' => 3307,
            'database' => 'our_blog_test',
            'username' => 'root',
            'password' => '123456',
            'charset'  => 'utf8mb4'
        )
    )
));

include 'phpunit-ext/loader.php';

abstract class OurBlog_DatabaseTestCase extends PHPUnit_DbUnit_Mysql_TestCase
{
    protected $mysqlDbname = 'our_blog_test';
}
