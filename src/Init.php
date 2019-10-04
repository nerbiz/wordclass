<?php

namespace Nerbiz\Wordclass;

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
     * Set the prefix to use for various things
     * @param string $prefix
     * @return void
     */
    public static function setPrefix(string $prefix): void
    {
        static::$prefix = $prefix;
    }

    /**
     * @return string
     */
    public static function getPrefix(): string
    {
        return static::$prefix;
    }

    /**
     * @param string $vendorUri
     * @return void
     */
    public static function setVendorPath(string $vendorUri): void
    {
        static::$vendorPath = rtrim($vendorUri, '/') . '/';
    }

    /**
     * Get the vendor path, optionally appended with an extra path
     * @param  string|null $path
     * @return string
     */
    public static function getVendorPath(?string $path = null): string
    {
        // The default value is the 'vendor' directory in a (child-)theme directory
        if(static::$vendorPath === null) {
            static::setVendorPath(get_stylesheet_directory() . '/vendor/');
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
     * @return void
     */
    public static function setVendorUri(string $vendorUri): void
    {
        static::$vendorUri = rtrim($vendorUri, '/') . '/';
    }

    /**
     * Get the vendor URI, optionally appended with an extra path
     * @param  string|null $path
     * @return string
     */
    public static function getVendorUri(?string $path = null): string
    {
        // The default value is the 'vendor' directory in a (child-)theme directory
        if (static::$vendorUri === null) {
            static::setVendorUri(get_stylesheet_directory_uri() . '/vendor/');
        }

        // Append the extra path if not null
        $vendorUri = static::$vendorUri;
        if ($path !== null) {
            $vendorUri .= $path;
        }

        return $vendorUri;
    }

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
    public function autoload(string $namespace, string $path, bool $relative = false): self
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
    public function defineConstants(): self
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
     * Load translation files
     * @return self
     */
    public function loadTranslations(): self
    {
        load_theme_textdomain(
            'wordclass',
            dirname(__FILE__, 2) . '/includes/languages'
        );

        return $this;
    }

    /**
     * Set the default timezone
     * @param string $timezoneString
     * @return self
     */
    public function setTimezone(string $timezoneString = 'auto'): self
    {
        if ($timezoneString === 'auto') {
            $timezoneString = get_option('timezone_string');
        }

        $timezoneString = trim($timezoneString);
        if ($timezoneString !== '') {
            date_default_timezone_set($timezoneString);
        }

        return $this;
    }

    /**
     * Include the functions file for convenience
     * @return self
     */
    public function includeHelperFunctions(): self
    {
        require_once __DIR__ . '/../includes/php/helper-functions.php';

        return $this;
    }
}
