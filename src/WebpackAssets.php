<?php

namespace Nerbiz\WordClass;

use stdClass;

class WebpackAssets extends Assets
{
    /**
     * Parsed contents of the manifest file
     * @var stdClass
     */
    protected stdClass $manifest;

    /**
     * @param string $distDirectory The name of the compiled assets directory
     */
    public function __construct(string $distDirectory = 'dist')
    {
        $distDirectory = trim($distDirectory, '/');

        $manifestPath = sprintf(
            '%s/%s/manifest.json',
            get_stylesheet_directory(),
            $distDirectory
        );

        $this->manifest = is_readable($manifestPath)
            ? json_decode(file_get_contents($manifestPath))
            : new stdClass();
    }

    /**
     * {@inheritdoc}
     */
    protected function parseAssetOptions(string $assetType, $options): array
    {
        $options = parent::parseAssetOptions($assetType, $options);
        $options['uri'] = ltrim($options['uri'], '/');

        $options['uri'] = (isset($this->manifest->{$options['uri']}))
            ? home_url($this->manifest->{$options['uri']})
            : '/' . $options['uri'];

        return $options;
    }
}
