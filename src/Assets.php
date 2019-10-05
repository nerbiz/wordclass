<?php

namespace Nerbiz\Wordclass;

class Assets
{
    /**
     * Add CSS asset(s) to the theme
     * @param array $assets handle:options pairs
     * @return self
     */
    public function addThemeCss(array $assets): self
    {
        return $this->addAssets('css', 'wp_enqueue_scripts', $assets);
    }

    /**
     * Add JavaScript asset(s) to the theme
     * @param array $assets handle:options pairs
     * @return self
     */
    public function addThemeJs(array $assets): self
    {
        return $this->addAssets('js', 'wp_enqueue_scripts', $assets);
    }

    /**
     * Add CSS asset(s) to admin
     * @param array $assets handle:options pairs
     * @return self
     */
    public function addAdminCss(array $assets): self
    {
        return $this->addAssets('css', 'admin_enqueue_scripts', $assets);
    }

    /**
     * Add JavaScript asset(s) to admin
     * @param array $assets handle:options pairs
     * @return self
     */
    public function addAdminJs(array $assets): self
    {
        return $this->addAssets('js', 'admin_enqueue_scripts', $assets);
    }

    /**
     * Add CSS asset(s) to the login screen
     * @param array $assets handle:options pairs
     * @return self
     */
    public function addLoginCss(array $assets): self
    {
        return $this->addAssets('css', 'login_enqueue_scripts', $assets);
    }

    /**
     * Add JavaScript asset(s) to the login screen
     * @param array $assets handle:options pairs
     * @return self
     */
    public function addLoginJs(array $assets): self
    {
        return $this->addAssets('js', 'login_enqueue_scripts', $assets);
    }

    /**
     * Add assets
     * @param string $assetType The type of asset, 'css' or 'js'
     * @param string $hook      The hook to register the assets in
     * @param array  $assets    handle:option pairs
     * @return self
     * @see Assets::parseAssetOptions()
     */
    protected function addAssets(string $assetType, string $hook, array $assets): self
    {
        add_action($hook, function () use ($assetType, $assets) {
            foreach ($assets as $handle => $options) {
                $options = $this->parseAssetOptions($assetType, $options);

                // Register the asset
                if ($assetType == 'css') {
                    wp_enqueue_style($handle, $options['uri'], $options['deps'], $options['ver'], $options['media']);
                } elseif ($assetType == 'js') {
                    wp_enqueue_script($handle, $options['uri'], $options['deps'], $options['ver'], $options['footer']);
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
     * deps: the assets that have to load before this one (default: none)
     * For css
     *   media: the 'media' attribute of the style tag (default: 'all')
     * For js
     *   footer: add this script to the header (false) or the footer (true) (default: true)
     * @return array
     */
    protected function parseAssetOptions(string $assetType, $options): array
    {
        // Convert the shorthand URI to an options array
        if (is_string($options)) {
            $options = ['uri' => $options];
        }

        // Prepend the URI with the (child)theme URI if it's relative
        if (! preg_match('~^(https?:)?//~', $options['uri'])) {
            $options['uri'] = '/' . ltrim($options['uri'], '/');
        }

        // Merge the options with default ones
        if ($assetType == 'css') {
            return array_replace([
                'deps'  => [],
                'ver'   => null,
                'media' => 'all',
            ], $options);
        } elseif ($assetType == 'js') {
            return array_replace([
                'deps'  => [],
                'ver'   => null,
                'footer' => true,
            ], $options);
        }
    }

    /**
     * Remove the jQuery asset
     * @return self
     */
    public function removeJquery(): self
    {
        add_action('init', function () {
            // Don't replace on admin
            if (! is_admin()) {
                // Remove the normal jQuery include
                wp_deregister_script('jquery');
            }
        });

        return $this;
    }

    /**
     * Replace the jQuery version with another one, using Google CDN
     * @param  string $version jQuery version to use
     * @return self
     */
    public function jQueryVersion(string $version): self
    {
        $this->removeJquery();

        add_action('init', function () use ($version) {
            global $pagenow;

            // Don't replace on admin
            if(! is_admin() && $pagenow !== 'wp-login.php') {
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
