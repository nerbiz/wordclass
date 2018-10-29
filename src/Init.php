<?php

namespace Nerbiz\Wordclass;

class Init
{
    /**
     * The default prefix to use
     * @var string
     */
    protected static $defaultPrefix = null;

    /**
     * The URI to the vendor directory
     * @var string
     */
    protected static $vendorUri = null;

    /**
     * An autoloader for custom namespaces
     * @param  string $namespace The name of the namespace
     * @param  string $path      The full path to the classes of the namespace
     * @param  bool   $relative  Remove the namespace from the path
     *                             For instance: a Custom\General class is located at classes/General.php
     *                             Then the autoloader shouldn't look for classes/Custom/General.php
     * @return self
     * @throws \Exception
     */
    public function autoload($namespace, $path, $relative = false)
    {
        spl_autoload_register(function ($class) use ($namespace, $path, $relative) {
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
            if ($relative) {
                // Get the position of the namespace in the path, and the length to remove
                $position = strrpos($classPath, $namespace);
                $replaceLength = strlen($namespace . DIRECTORY_SEPARATOR);

                // Construct the new path, without the namespace in it
                $start = substr($classPath, 0, $position);
                $end = substr($classPath, ($position + $replaceLength));
                $classPath = $start . $end;
            }

            if (is_readable($classPath)) {
                require_once $classPath;
            }
        });

        return $this;
    }

    /**
     * Define some useful constants
     * @return self
     */
    public function defineConstants()
    {
        // The absolute paths to the template/stylesheet directory
        define('WC_THEME_PATH', get_template_directory() . '/');
        define('WC_TEMPLATE_PATH', WC_THEME_PATH);
        define('WC_STYLESHEET_PATH', get_stylesheet_directory() . '/');

        // The URI paths to the template/stylesheet directory
        define('WC_THEME_URI', get_template_directory_uri() . '/');
        define('WC_TEMPLATE_URI', WC_THEME_URI);
        define('WC_STYLESHEET_URI', get_stylesheet_directory_uri() . '/');

        return $this;
    }

    /**
     * @param string $defaultPrefix
     * @return self
     */
    public function setDefaultPrefix($defaultPrefix)
    {
        self::$defaultPrefix = $defaultPrefix;

        return $this;
    }

    /**
     * @param  string  $prefix
     * @return string
     */
    public static function getDefaultPrefix($prefix = null)
    {
        return static::$defaultPrefix;
    }

    /**
     * @param string $vendorUri
     * @return self
     */
    public function setVendorUri($vendorUri)
    {
        static::$vendorUri = $vendorUri;

        return $this;
    }

    /**
     * @return string
     */
    public static function getVendorUri()
    {
        // The default value is the 'vendor' directory in a (child-)theme directory
        if (static::$vendorUri === null) {
            static::$vendorUri = get_stylesheet_directory_uri() . '/vendor/';
        }

        return static::$vendorUri;
    }
}
