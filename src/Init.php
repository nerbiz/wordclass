<?php

namespace Wordclass;

class Init {
    private static $_defaultTextDomain = null;

    private static $_prefix = null;

    private static $_vendorUri = null;



    /**
     * Initialize the autoloader
     * Only needed when not using the Composer autoloader
     */
    public static function autoloader() {
        spl_autoload_register(function($class) {
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
     * Set or get the default text domain
     * @param  String  $domain
     * @return String
     */
    public static function defaultTextDomain($domain=null) {
        if($domain !== null)
            static::$_defaultTextDomain = $domain;
        else
            return static::$_defaultTextDomain;
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
     * Set or get the vendor directory URI
     * @param  String  $uri
     * @return String
     */
    public static function vendorUri($uri=null) {
        if($uri)
            static::$_vendorUri = rtrim($uri, '/') . '/';

        else {
            // The default value is the 'vendor' directory in a (child-)theme directory
            if(static::$_vendorUri == null)
                static::$_vendorUri = get_stylesheet_directory_uri() . '/vendor/';

            return static::$_vendorUri;
        }
    }
}
