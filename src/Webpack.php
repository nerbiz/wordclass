<?php

namespace Nerbiz\Wordclass;

use Exception;

class Webpack
{
    /**
     * The parsed Webpack manifest
     * @var stdClass
     */
    protected static $manifest;

    /**
     * Read a manifest file
     * @param  string|null $path The full path to the manifest file, default path if null
     * @return void
     * @throws Exception If the path is not readable
     */
    public static function readManifest(?string $path = null): void
    {
        if ($path === null) {
            $path = get_stylesheet_directory() . '/dist/manifest.json';
        }

        if (! is_readable($path)) {
            throw new Exception(sprintf(
                "%s(): the manifest.json path '%s' is not readable",
                __METHOD__,
                is_object($path) ? get_class($path) : $path
            ));
        }

        // Read the manifest
        static::$manifest = json_decode(file_get_contents($path));
    }

    /**
     * Get an asset URL by the original name
     * @param  string $originalFilename
     * @return string|null
     * @throws Exception If the asset is not found
     */
    public static function getAssetUrl(string $originalFilename): ?string
    {
        if (isset(static::$manifest->{$originalFilename})) {
            return esc_url(home_url(static::$manifest->{$originalFilename}));
        }

        throw new \Exception(sprintf(
            "%s(): asset '%s' not found in the manifest",
            __METHOD__,
            $originalFilename
        ));
    }
}
