<?php

namespace Wordclass;

class Init {
    /**
     * The default prefix to use
     * @var String
     */
    private static $_prefix = null;

    /**
     * The path to the vendor directory
     * The default is the 'vendor' directory in the theme directory
     * @var String
     */
    private static $_vendorPath = null;

    /**
     * The URI to the vendor directory
     * The default is the 'vendor' directory in the theme directory
     * @var String
     */
    private static $_vendorUri = null;



    /**
     * An autoloader for custom namespaces
     * @param String  $namespace  The name of the namespace
     * @param String  $path       The full path to the classes of the namespace
     * @param Boolean $remove     Remove the namespace from the path
     *                              For instance: a Custom\General class is located at classes/General.php
     *                              Then the autoloader shouldn't look for classes/Custom/General.php
     */
    public static function autoload($namespace, $path, $remove=false) {
        spl_autoload_register(function($class) use($namespace, $path, $remove) {
            // Ensure 1 trailing slash
            $path = rtrim($path, '/') . '/';

            // Remove leading backslashes
            $classPath = ltrim($class, '\\');

            // Replace backslashes with directory separators
            $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $classPath);

            // Construct the classpath
            $classPath = $path . $classPath . '.php';

            // Remove the namespace from the path, if needed
            // Only the last occurence, because another directory might have the same name
            if($remove) {
                // Get the position of the namespace in the path, and the length to remove
                $position = strrpos($classPath, $namespace);
                $replaceLength = strlen($namespace . DIRECTORY_SEPARATOR);

                // Construct the new path, without the namespace in it
                $start = substr($classPath, 0, $position);
                $end = substr($classPath, ($position + $replaceLength));
                $classPath = $start . $end;
            }

            if(is_readable($classPath))
                require_once $classPath;
        });
    }



    /**
     * Define some useful constants
     */
    public static function constants() {
        // The absolute paths to the template/stylesheet directory
        define('TEMPLATE_PATH', get_template_directory() . '/');
        define('STYLESHEET_PATH', get_stylesheet_directory() . '/');

        // The URI paths to the template/stylesheet directory
        define('TEMPLATE_URI', get_template_directory_uri() . '/');
        define('STYLESHEET_URI', get_stylesheet_directory_uri() . '/');
    }



    /**
     * Set or get the default prefix
     * @param  String  $prefix
     * @return String
     */
    public static function defaultPrefix($prefix=null) {
        if($prefix !== null)
            static::$_prefix = $prefix;
        else
            return static::$_prefix;
    }



    /**
     * Set or get the vendor directory path
     * @param  String  $path
     * @return String
     */
    public static function vendorPath($path=null) {
        if($path)
            static::$_vendorPath = rtrim($path, '/') . '/';

        else {
            // The default value is the 'vendor' directory in a (child-)theme directory
            if(static::$_vendorPath === null)
                static::$_vendorPath = get_stylesheet_directory() . '/vendor/';

            return static::$_vendorPath;
        }
    }



    /**
     * Set or get the vendor directory URI
     * @param  String  $uri
     * @return String
     */
    public static function vendorUri($uri=null) {
        if($uri)
            static::$_vendorUri = rtrim($uri, '/') . '/';

        else {
            // The default value is the 'vendor' directory in a (child-)theme directory
            if(static::$_vendorUri === null)
                static::$_vendorUri = get_stylesheet_directory_uri() . '/vendor/';

            return static::$_vendorUri;
        }
    }
}
