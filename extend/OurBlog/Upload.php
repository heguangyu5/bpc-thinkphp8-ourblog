<?php

class OurBlog_Upload
{
    public static $unitTest = false;

    protected $uploadDir;
    protected $uid;

    public function __construct($uploadDir, $uid)
    {
        $this->uploadDir = $uploadDir;
        $this->uid       = $uid;
    }

    public function upload()
    {
        if (!isset($_FILES['file'])) {
            throw new InvalidArgumentException('file not upload');
        }
        $file = $_FILES['file'];
        if ($file['error'] != UPLOAD_ERR_OK) {
            throw new InvalidArgumentException('upload error');
        }
        // name
        $filename = trim($file['name']);
        $len = strlen($filename);
        if ($len == 0 || $len > 200) {
            throw new InvalidArgumentException('filename too long');
        }
        if (strpos($filename, '/') !== false
            || strpos($filename, '\\') !== false
            || $filename == '.'
            || $filename == '..'
        ) {
            throw new InvalidArgumentException('invalid filename');
        }
        // image
        $ext = explode('.', $filename);
        $ext = strtolower(array_pop($ext));
        $mimeTypes = array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif'
        );
        if (isset($mimeTypes[$ext])) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $type  = $finfo->file($file['tmp_name']);
            if ($type != $mimeTypes[$ext]) {
                throw new InvalidArgumentException("invalid $ext image");
            }
            $filename = $this->uid . '-' . $filename;
            $dstPath  = $this->uploadDir . '/' . $filename;
        } else {
            throw new Exception('not implement');
        }

        if (file_exists($dstPath)) {
            return $filename;
        }
        if (self::$unitTest) {
            copy($file['tmp_name'], $dstPath);
        } else {
            if (!move_uploaded_file($file['tmp_name'], $dstPath)) {
                throw new InvalidArgumentException('move_uploaded_file failed');
            }
        }
        return $filename;
    }
}
