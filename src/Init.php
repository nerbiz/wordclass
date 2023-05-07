<?php

namespace Nerbiz\WordClass;

class Init
{
    /**
     * The default prefix to use
     * @var string
     */
    protected static string $prefix = 'nw';

    /**
     * The path to the vendor directory
     * @var string
     */
    protected static string $vendorPath;

    /**
     * The URI to the vendor directory
     * @var string
     */
    protected static string $vendorUri;

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
     * @param string $vendorPath
     * @return void
     */
    public static function setVendorPath(string $vendorPath): void
    {
        static::$vendorPath = rtrim($vendorPath, '/') . '/';
    }

    /**
     * Get the vendor path, optionally appended with an extra path
     * @param  string|null $path
     * @return string
     */
    public static function getVendorPath(?string $path = null): string
    {
        // The default value is the 'vendor' directory in the (child-)theme directory
        if (! isset(static::$vendorPath)) {
            static::setVendorPath(get_stylesheet_directory() . '/vendor/');
        }

        return static::$vendorPath . $path;
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
        // The default value is the 'vendor' directory in the (child-)theme directory
        if (! isset(static::$vendorUri)) {
            static::setVendorUri(get_stylesheet_directory_uri() . '/vendor/');
        }

        return static::$vendorUri . $path;
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
     * Include the functions file for convenience
     * @return self
     */
    public function includeHelperFunctions(): self
    {
        require_once dirname(__FILE__, 2) . '/includes/php/helper-functions.php';

        return $this;
    }
}
