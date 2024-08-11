<?php

require 'think-helper/think/helper.php';

spl_autoload_register(function ($class) {
    if (defined('__BPC__')) {
        require str_replace(array('_', '\\'), '/', $class) . '.php';
    } else {
        if (strncmp($class, 'think\\', 6) === 0) {
            $includePath = '/usr/share/php';
            $thinkDirs   = array('topthink-framework/src', 'think-orm', 'think-helper');
            $file        = str_replace('\\', '/', $class) . '.php';
            foreach ($thinkDirs as $dir) {
                $path = "$includePath/$dir/$file";
                if (file_exists($path)) {
                    require $path;
                    return;
                }
            }
        } elseif (   strncmp($class, 'Psr\\', 4) === 0
                  || strncmp($class, 'app\\', 4) === 0
        ) {
            require str_replace('\\', '/', $class) . '.php';
        } else {
            require str_replace('_', '/', $class) . '.php';
        }
    }
});
