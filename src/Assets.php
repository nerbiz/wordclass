<?php

namespace Nerbiz\WordClass;

class Assets
{
    /**
     * Add CSS asset(s) to the theme
     * @param string       $handle
     * @param string|array $options
     * @return self
     */
    public function addThemeCss(string $handle, $options): self
    {
        return $this->addAssets('css', 'wp_enqueue_scripts', $handle, $options);
    }

    /**
     * Add JavaScript asset(s) to the theme
     * @param string       $handle
     * @param string|array $options
     * @return self
     */
    public function addThemeJs(string $handle, $options): self
    {
        return $this->addAssets('js', 'wp_enqueue_scripts', $handle, $options);
    }

    /**
     * Add CSS asset(s) to admin
     * @param string       $handle
     * @param string|array $options
     * @return self
     */
    public function addAdminCss(string $handle, $options): self
    {
        return $this->addAssets('css', 'admin_enqueue_scripts', $handle, $options);
    }

    /**
     * Add JavaScript asset(s) to admin
     * @param string       $handle
     * @param string|array $options
     * @return self
     */
    public function addAdminJs(string $handle, $options): self
    {
        return $this->addAssets('js', 'admin_enqueue_scripts', $handle, $options);
    }

    /**
     * Add CSS asset(s) to the login screen
     * @param string       $handle
     * @param string|array $options
     * @return self
     */
    public function addLoginCss(string $handle, $options): self
    {
        return $this->addAssets('css', 'login_enqueue_scripts', $handle, $options);
    }

    /**
     * Add JavaScript asset(s) to the login screen
     * @param string       $handle
     * @param string|array $options
     * @return self
     */
    public function addLoginJs(string $handle, $options): self
    {
        return $this->addAssets('js', 'login_enqueue_scripts', $handle, $options);
    }

    /**
     * Add assets
     * @param string       $assetType The type of asset, 'css' or 'js'
     * @param string       $hook      The hook to register the assets in
     * @param string       $handle
     * @param string|array $options
     * @return self
     * @see Assets::parseAssetOptions()
     */
    protected function addAssets(string $assetType, string $hook, string $handle, $options): self
    {
        add_action($hook, function () use ($assetType, $handle, $options) {
            $options = $this->parseAssetOptions($assetType, $options);

            // Register the asset
            if ($assetType === 'css') {
                wp_enqueue_style($handle, $options['uri'], $options['deps'],
                    $options['ver'], $options['media']);
            } elseif ($assetType === 'js') {
                wp_enqueue_script($handle, $options['uri'], $options['deps'],
                    $options['ver'], $options['footer']);
            }
        });

        return $this;
    }

    /**
     * Parse asset options for registering
     * @param string       $assetType The type of asset, 'css' or 'js'
     * @param array|string $options   Either an options array, or only a URI
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
        if ($assetType === 'css') {
            return array_replace([
                'deps'  => [],
                'ver'   => null,
                'media' => 'all',
            ], $options);
        } elseif ($assetType === 'js') {
            return array_replace([
                'deps'  => [],
                'ver'   => null,
                'footer' => true,
            ], $options);
        }
    }

    /**
     * Replace 'ver=' asset parameter values with a hash
     * @param string $salt
     * @return self
     */
    public function hashVersionParameters(string $salt): self
    {
        $applyHash = function (string $url) use ($salt): string
        {
            if (stripos($url, 'ver=') !== false) {
                $url = preg_replace_callback(
                    '~(?<=[?&]ver=)(?<version>[^&]+)~i',
                    function (array $matches) use ($salt) {
                        return hash('sha256', $matches['version'] . $salt);
                    },
                    $url
                );
            }

            return $url;
        };

        add_filter('style_loader_src', $applyHash);
        add_filter('script_loader_src', $applyHash);

        return $this;
    }
}
