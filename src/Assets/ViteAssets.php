<?php

namespace Nerbiz\WordClass\Assets;

use stdClass;

class ViteAssets extends Assets
{
    /**
     * The name of the compiled assets directory
     * @var string
     */
    protected string $relativeDistPath;

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
     * @param string|null $distDirectory The full path of the compiled assets directory
     */
    public function __construct(?string $distDirectory = null)
    {
        $distDirectory ??= get_stylesheet_directory() . '/dist';
        $distDirectory = rtrim($distDirectory, '/');
        $this->relativeDistPath = str_replace(ABSPATH, '', $distDirectory);

        $manifestFile = sprintf(
            '%s/%s/manifest.json',
            ABSPATH,
            $this->relativeDistPath
        );

        $this->manifest = is_readable($manifestFile)
            ? json_decode(file_get_contents($manifestFile))
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
        $this->addThemeJs('vite-client', '@vite/client');
        $this->addAdminJs('vite-client', '@vite/client');
        $this->addLoginJs('vite-client', '@vite/client');
        $this->moduleHandles[] = 'vite-client';

        // Set the type to 'module' for applicable scripts
        add_filter('script_loader_tag', function (string $tag, string $handle) {
            if (in_array($handle, $this->moduleHandles, true)) {
                return (stripos($tag, 'type=') !== false)
                    // Replace the 'type' attribute value with "module"
                    ? preg_replace('/(?<=type=)(["\']).*?\1/i', '"module"', $tag)
                    // Add the 'type' attribute if it doesn't exist
                    : str_replace('src=', 'type="module" src=', $tag);
            }

            return $tag;
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
                $options['uri'] = get_site_url(null, $this->relativeDistPath . $actualPath);
            } else {
                $options['uri'] = '/' . $options['uri'];
            }
        }

        return $options;
    }
}
