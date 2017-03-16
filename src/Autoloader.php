<?php

namespace Wordclass;

class Autoloader {
    public function __construct() {
        // Register the autoloader
        spl_autoload_register('static::find');
    }



    /**
     * (Try to) find a class, and require it, if found
     * @param  String  $class
     */
    public static function find($class) {
        $classPath = ltrim($class, '\\');
        $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $classPath);
        $classPath = __DIR__ . '/../' . $classPath . '.php';

        if(is_readable($classPath))
            require_once $classPath;
    }
}
