<?php

namespace Nerbiz\Wordclass;

class Webpack
{
    /**
     * The parsed Webpack manifest
     * @var stdClass
     */
    protected static $manifest;

    public function __construct()
    {
        // Empty object, fallback in case a file is never parsed
        if (static::$manifest === null) {
            static::$manifest = (object) [];
        }
    }

    /**
     * Parse a manifest file
     * @param  string|null $path The full path to manifest.json, default path if null
     * @return self
     * @throws \Exception If the path is not readable
     */
    public function parse($path = null)
    {
        if ($path === null) {
            $path = get_template_directory() . '/dist/manifest.json';
        }

        if (! is_readable($path)) {
            throw new \Exception(sprintf(
                "%s(): the manifest.json path '%s' is not readable",
                __METHOD__,
                is_object($path) ? get_class($path) : $path
            ));
        }

        // Set the parsed manifest
        static::$manifest = json_decode(file_get_contents($path));

        return $this;
    }

    /**
     * Get an asset URL by the original name
     * @param  string $originalFilename
     * @return string|null
     */
    public static function getAsset($originalFilename)
    {
        if (property_exists(static::$manifest, $originalFilename)) {
            return static::$manifest->{$originalFilename};
        }

        // Trigger a 404 error if not found
        http_response_code(404);
        return null;
    }
}
