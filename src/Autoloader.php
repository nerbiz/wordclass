<?php

namespace Wordclass;

class Autoloader {
    /**
     * Initialize the autoloader
     * Only needed when not using the Composer autoloader
     */
    public static function init() {
        spl_autoload_register('static::find');
    }



    /**
     * (Try to) find a class, and load it, if found
     * @param  String  $class
     */
    public static function find($class) {
        $slash = DIRECTORY_SEPARATOR;
        $classPath = ltrim($class, '\\');
        $classPath = str_replace('\\', $slash, $classPath);
        $classPath = __DIR__ . $slash . $classPath . '.php';
        $classPath = str_replace($slash.'Wordclass'.$slash, $slash, $classPath);

        if(is_readable($classPath))
            require_once $classPath;
    }
}
