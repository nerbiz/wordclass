<?php

namespace Nerbiz\Wordclass;

class Assets
{
    /**
     * This string is appended to all asset URLs, if cache busting is enabled
     * @var string
     */
    protected $assetAppend = '';

    /**
     * Enable cache busting by appending asset URIs
     * @return self
     */
    public function enableCacheBusting()
    {
        $this->assetAppend = '?v=' . time();

        return $this;
    }

    /**
     * Disable cache busting
     * @return self
     */
    public function disableCacheBusting()
    {
        $this->assetAppend = '';

        return $this;
    }

    /**
     * Add CSS asset(s) to the theme
     * @param array $assets handle:options pairs
     * @return self
     */
    public function addThemeCss(array $assets)
    {
        return $this->addAssets('css', 'wp_enqueue_scripts', $assets);
    }

    /**
     * Add JavaScript asset(s) to the theme
     * @param array $assets handle:options pairs
     * @return self
     */
    public function addThemeJs(array $assets)
    {
        return $this->addAssets('js', 'wp_enqueue_scripts', $assets);
    }

    /**
     * Add CSS asset(s) to admin
     * @param array $assets handle:options pairs
     * @return self
     */
    public function addAdminCss(array $assets)
    {
        return $this->addAssets('css', 'admin_enqueue_scripts', $assets);
    }

    /**
     * Add JavaScript asset(s) to admin
     * @param array $assets handle:options pairs
     * @return self
     */
    public function addAdminJs(array $assets)
    {
        return $this->addAssets('js', 'admin_enqueue_scripts', $assets);
    }

    /**
     * Add CSS asset(s) to the login screen
     * @param array $assets handle:options pairs
     * @return self
     */
    public function addLoginCss(array $assets)
    {
        return $this->addAssets('css', 'login_enqueue_scripts', $assets);
    }

    /**
     * Add JavaScript asset(s) to the login screen
     * @param array $assets handle:options pairs
     * @return self
     */
    public function addLoginJs(array $assets)
    {
        return $this->addAssets('js', 'login_enqueue_scripts', $assets);
    }

    /**
     * Add assets
     * @param string $assetType The type of asset, 'css' or 'js'
     * @param string $hook      The hook to register the assets in
     * @param array $assets     handle:option pairs
     * @return self
     * @see self::parseAssetOptions()
     */
    protected function addAssets($assetType, $hook, array $assets)
    {
        add_action($hook, function () use ($assetType, $assets) {
            foreach ($assets as $handle => $options) {
                $options = $this->parseAssetOptions($assetType, $options);

                // Register the asset
                if ($assetType == 'css') {
                    wp_enqueue_style($handle, $options['uri'], $options['after'], null, $options['media']);
                } elseif ($assetType == 'js') {
                    wp_enqueue_script($handle, $options['uri'], $options['after'], null, $options['footer']);
                }
            }
        });

        return $this;
    }

    /**
     * Parse asset options for registering
     * @param string       $assetType The type of asset, 'css' or 'js'
     * @param array|string $options   Either an options array, or only a URI (string)
     * Options:
     * uri: URI to the file
     * after: the assets that have to load before this one (default: none)
     * For css
     *   media: the 'media' attribute of the style tag (default: 'all')
     * For js
     *   footer: add this script to the header (false) or the footer (true) (default: true)
     * @return array
     */
    protected function parseAssetOptions($assetType, $options)
    {
        // Convert the shorthand URI to an options array
        if (is_string($options)) {
            $options = ['uri' => $options];
        }

        // Prepend the URI with the (child)theme URI if it's relative
        if (! preg_match('~^(https?:)?//~', $options['uri'])) {
            $options['uri'] = sprintf(
                '%s/%s%s',
                get_stylesheet_directory_uri(),
                $options['uri'],
                $this->assetAppend
            );
        }

        // Merge the options with default ones
        if ($assetType == 'css') {
            return array_merge([
                'after' => [],
                'media' => 'all'
            ], $options);
        } elseif ($assetType == 'js') {
            return array_merge([
                'after'  => [],
                'footer' => true
            ], $options);
        }
    }

    /**
     * Replace the jQuery version with another one, using Google CDN
     * @param  string $version jQuery version to use
     * @return self
     */
    public function jQueryVersion($version)
    {
        add_action('init', function () use ($version) {
            // Don't replace on admin
            if (! is_admin()) {
                // Remove the normal jQuery include
                wp_deregister_script('jquery');

                // Set the custom one
                wp_enqueue_script(
                    'jquery',
                    sprintf('//ajax.googleapis.com/ajax/libs/jquery/%s/jquery.min.js', $version),
                    [],
                    $version
                );
            }
        });

        return $this;
    }
}
