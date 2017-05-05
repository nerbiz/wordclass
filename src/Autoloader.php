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
        $sep = DIRECTORY_SEPARATOR;
        // Remove leading and trailing backslashes
        $classPath = ltrim($class, '\\');
        // Replace backslashes with directory separators
        $classPath = str_replace('\\', $sep, $classPath);
        // Construct the classpath, relative to this file
        $classPath = __DIR__ . $sep . $classPath . '.php';
        // Remove the 'Wordclass' namespace from the path
        $classPath = str_replace($sep.'Wordclass'.$sep, $sep, $classPath);

        if(is_readable($classPath))
            require_once $classPath;
    }
}
