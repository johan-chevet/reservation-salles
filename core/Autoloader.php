<?php

namespace Core;

class Autoloader
{
    public static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    public static function autoload(string $class_name)
    {
        $class = str_replace('\\', '/', $class_name);
        $file = ROOT_PATH . "/$class" . '.php';
        if (is_file($file)) {
            require ROOT_PATH . "/$class" . '.php';
            return true;
        }
        return false;
    }
}
