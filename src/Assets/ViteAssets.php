<?php

namespace Nerbiz\WordClass\Assets;

use stdClass;

class ViteAssets extends Assets
{
    /**
     * The name of the compiled assets directory
     * @var string
     */
    protected string $distDirectory;

    /**
     * Parsed contents of the manifest file
     * @var stdClass
     */
    protected stdClass $manifest;

    /**
     * The address of the development server
     * @var string|null
     */
    protected ?string $devServer = null;

    /**
     * Handles of scripts that should be modules (for dev server)
     * @var array
     */
    protected array $moduleHandles = [];

    /**
     * @param string $distDirectory The name of the compiled assets directory
     */
    public function __construct(string $distDirectory = 'dist')
    {
        $this->distDirectory = trim($distDirectory, '/');

        $manifestPath = sprintf(
            '%s/%s/manifest.json',
            get_stylesheet_directory(),
            $this->distDirectory
        );

        $this->manifest = is_readable($manifestPath)
            ? json_decode(file_get_contents($manifestPath))
            : new stdClass();
    }

    /**
     * @param string $address
     * @return self
     */
    public function useDevServer(string $address = 'https://localhost:5173'): self
    {
        $this->devServer = rtrim($address, '/') . '/';

        // Add the dev server script
        add_action('wp_enqueue_scripts', function () {
            $serverUrl = $this->devServer . '@vite/client';
            wp_enqueue_script('vite-client', $serverUrl, [], null, true);

            $this->moduleHandles[] = 'vite-client';
        });

        // Set the type to 'module' for applicable scripts
        add_filter('script_loader_tag', function ($tag, $handle) {
            return (in_array($handle, $this->moduleHandles))
                ? str_replace('text/javascript', 'module', $tag)
                : $tag;
        }, 10, 2);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function add(string $hook, string $assetType, string $handle, array|string $options): self
    {
        if ($this->devServer !== null && $assetType === 'js') {
            $this->moduleHandles[] = $handle;
        }

        return parent::add($hook, $assetType, $handle, $options);
    }

    /**
     * {@inheritdoc}
     */
    protected function parseOptions(string $assetType, array|string $options): array
    {
        $options = parent::parseOptions($assetType, $options);

        if ($this->devServer !== null) {
            $options['uri'] = $this->devServer . $options['uri'];
        } else {
            // Get the actual asset path from the manifest
            $actualPath = $this->manifest->{$options['uri']}->file ?? null;

            if (isset($actualPath)) {
                $actualPath = '/' . ltrim($actualPath, '/');
                $options['uri'] = get_theme_file_uri($this->distDirectory . $actualPath);
            } else {
                $options['uri'] = '/' . $options['uri'];
            }
        }

        return $options;
    }
}
