<?php

namespace Nerbiz\Wordclass;

use Composer\Autoload\ClassLoader;

class Init
{
    /**
     * The default prefix to use
     * @var string
     */
    protected static $prefix = 'nw';

    /**
     * The path to the vendor directory
     * @var string
     */
    protected static $vendorPath;

    /**
     * The URI to the vendor directory
     * @var string
     */
    protected static $vendorUri;

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
        define(strtoupper(static::$prefix) . '_THEME_PATH', get_template_directory() . '/');
        define(strtoupper(static::$prefix) . '_TEMPLATE_PATH', constant(strtoupper(static::$prefix) . '_THEME_PATH'));
        define(strtoupper(static::$prefix) . '_STYLESHEET_PATH', get_stylesheet_directory() . '/');

        // The URI paths to the template/stylesheet directory
        define(strtoupper(static::$prefix) . '_THEME_URI', get_template_directory_uri() . '/');
        define(strtoupper(static::$prefix) . '_TEMPLATE_URI', constant(strtoupper(static::$prefix) . '_THEME_URI'));
        define(strtoupper(static::$prefix) . '_STYLESHEET_URI', get_stylesheet_directory_uri() . '/');

        return $this;
    }

    /**
     * Set the prefix to use for various things
     * @param string $prefix
     * @return self
     */
    public function setPrefix($prefix)
    {
        static::$prefix = $prefix;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return static::$prefix;
    }

    /**
     * @param $vendorUri
     * @return self
     */
    public function setVendorPath($vendorUri)
    {
        static::$vendorPath = rtrim($vendorUri, '/') . '/';

        return $this;
    }

    /**
     * Get the vendor path, optionally appended with an extra path
     * @param  string $path
     * @return string
     * @throws \ReflectionException
     */
    public function getVendorPath($path = null)
    {
        // Set the default path, if not set yet
        if (static::$vendorPath === null) {
            // Derive the vendor path from the location of the Composer classloader
            $reflection = new \ReflectionClass(ClassLoader::class);
            $vendorPath = dirname(dirname($reflection->getFileName()));
            $this->setVendorPath($vendorPath);
        }

        // Append the extra path if not null
        $vendorPath = static::$vendorPath;
        if ($path !== null) {
            $vendorPath .= $path;
        }

        return $vendorPath;
    }

    /**
     * @param string $vendorUri
     * @return self
     */
    public function setVendorUri($vendorUri)
    {
        static::$vendorUri = rtrim($vendorUri, '/') . '/';

        return $this;
    }

    /**
     * Get the vendor URI, optionally appended with an extra path
     * @param  string $path
     * @return string
     * @throws \ReflectionException
     */
    public function getVendorUri($path = null)
    {
        // The default value is the 'vendor' directory in a (child-)theme directory
        if (static::$vendorUri === null) {
            // Get the relative path by removing the document root from the full path
            $vendorUri = home_url(str_replace($_SERVER['DOCUMENT_ROOT'], '', $this->getVendorPath()));
            $this->setVendorUri($vendorUri);
        }

        // Append the extra path if not null
        $vendorUri = static::$vendorUri;
        if ($path !== null) {
            $vendorUri .= $path;
        }

        return $vendorUri;
    }
}
