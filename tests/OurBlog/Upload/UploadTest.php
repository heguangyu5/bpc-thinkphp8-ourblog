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
        $cwd = TEST_ROOT_DIR . '/OurBlog/Upload';
        mkdir($cwd . '/upload');
        copy($cwd . '/ourats.png', $_FILES['file']['tmp_name']);

        $upload   = new OurBlog_Upload($cwd . '/upload', 1);
        $filename = $upload->upload();

        $this->assertEquals('1-ourats.png', $filename);
        $this->assertFileEquals(
            $cwd . '/upload/1-ourats.png',
            $cwd . '/ourats.png'
        );

        unlink($cwd . '/upload/1-ourats.png');
        rmdir($cwd . '/upload');
    }
}
