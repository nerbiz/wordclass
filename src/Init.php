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
     * Load translation files
     * @return void
     */
    public static function loadTranslations(): void
    {
        load_theme_textdomain(
            'wordclass',
            dirname(__FILE__, 2) . '/languages'
        );
    }

    /**
     * Include the functions file for convenience
     * @return void
     */
    public static function includeHelperFunctions(): void
    {
        require_once dirname(__FILE__, 2) . '/includes/php/helper-functions.php';
    }

    /**
     * Get the path to this package, optionally appended with an extra path
     * @param  string|null $path
     * @return string
     */
    public static function getPackagePath(?string $path = null): string
    {
        if (! isset(static::$vendorPath)) {
            $relativePath = rtrim(str_replace(ABSPATH, '', dirname(__DIR__)), '/') . '/';
            static::$vendorPath = ABSPATH . $relativePath;
        }

        return static::$vendorPath . $path;
    }

    /**
     * Get the URI to this package, optionally appended with an extra path
     * @param  string|null $path
     * @return string
     */
    public static function getPackageUri(?string $path = null): string
    {
        if (! isset(static::$vendorUri)) {
            $relativePath = rtrim(str_replace(ABSPATH, '', dirname(__DIR__)), '/') . '/';
            static::$vendorUri = get_site_url(null, $relativePath);
        }

        return static::$vendorUri . $path;
    }
}
