<?php

namespace Nerbiz\Wordclass;

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
                wp_enqueue_style($handle, $options['uri'], $options['deps'], $options['ver'], $options['media']);
            } elseif ($assetType === 'js') {
                wp_enqueue_script($handle, $options['uri'], $options['deps'], $options['ver'], $options['footer']);
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
     * Remove the jQuery asset
     * @return self
     */
    public function removeJquery(): self
    {
        add_action('init', function () {
            global $pagenow;

            // Don't replace on admin
            if (! is_admin() && $pagenow !== 'wp-login.php') {
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
            if (! is_admin() && $pagenow !== 'wp-login.php') {
                wp_enqueue_script(
                    'jquery',
                    sprintf('//ajax.googleapis.com/ajax/libs/jquery/%s/jquery.min.js', $version),
                    [],
                    $version,
                    true
                );
            }
        });

        return $this;
    }

    /**
     * Replace the hostname in image URLs, for local development with remote images
     * @param string $newHost The hostname to use instead of the current hostname
     * @param array  $environments The environments in which to use the other host
     * @return self
     */
    public function replaceImageUrlsHost(
        string $newHost,
        array $environments = ['local', 'development']
    ): self {
        // Only replace the host during development/debugging
        if (function_exists('wp_get_environment_type')) {
            $environment = wp_get_environment_type();
            if (! in_array($environment, $environments, true)) {
                return $this;
            }
        } else if (WP_DEBUG === false) {
            return $this;
        }

        add_filter('wp_get_attachment_image_src', function ($image) use ($newHost) {
            if (isset($image[0])) {
                $image[0] = str_replace($_SERVER['HTTP_HOST'], $newHost, $image[0]);
            }

            return $image;
        }, 10, 4);

        add_filter('wp_get_attachment_url', function (string $url) use ($newHost) {
            return str_replace($_SERVER['HTTP_HOST'], $newHost, $url);
        }, 10, 2);

        return $this;
    }
}
