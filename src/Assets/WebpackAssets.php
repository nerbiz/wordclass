<?php

namespace Nerbiz\WordClass\Assets;

use stdClass;

class WebpackAssets extends Assets
{
    /**
     * Parsed contents of the manifest file
     * @var stdClass
     */
    protected stdClass $manifest;

    /**
     * @param string|null $distDirectory The full path of the compiled assets directory
     */
    public function __construct(?string $distDirectory = null)
    {
        $distDirectory ??= get_stylesheet_directory() . '/dist';
        $distDirectory = rtrim($distDirectory, '/');
        $manifestFile = $distDirectory . '/manifest.json';

        $this->manifest = is_readable($manifestFile)
            ? json_decode(file_get_contents($manifestFile))
            : new stdClass();
    }

    /**
     * {@inheritdoc}
     */
    protected function parseOptions(string $assetType, array|string $options): array
    {
        $options = parent::parseOptions($assetType, $options);
        $options['uri'] = $this->manifest->{$options['uri']} ?? '/' . ltrim($options['uri'], '/');

        return $options;
    }
}
