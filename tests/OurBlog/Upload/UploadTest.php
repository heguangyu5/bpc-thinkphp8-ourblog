<?php

class OurBlog_UploadTest extends PHPUnit_Framework_TestCase
{
    static $classGroups = 'upload';

    public function testUpload()
    {
        $_FILES = array(
            'file' => array(
                'name'     => 'ourats.png',
                'type'     => 'image/png',
                'size'     => 7386,
                'tmp_name' => '/tmp/php42up23',
                'error'    => UPLOAD_ERR_OK
            )
        );

        OurBlog_Upload::$unitTest = true;
        mkdir(__DIR__ . '/upload');
        copy(__DIR__ . '/ourats.png', $_FILES['file']['tmp_name']);

        $upload   = new OurBlog_Upload(__DIR__ . '/upload', 1);
        $filename = $upload->upload();

        $this->assertEquals('1-ourats.png', $filename);
        $this->assertFileEquals(
            __DIR__ . '/upload/1-ourats.png',
            __DIR__ . '/ourats.png'
        );

        unlink(__DIR__ . '/upload/1-ourats.png');
        rmdir(__DIR__ . '/upload');
    }
}
