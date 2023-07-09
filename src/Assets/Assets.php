<?php

namespace Nerbiz\WordClass\Assets;

class Assets
{
    /**
     * Hooks for adding assets to different locations
     * @var string
     */
    const HOOK_THEME = 'wp_enqueue_scripts';
    const HOOK_ADMIN = 'admin_enqueue_scripts';
    const HOOK_LOGIN = 'login_enqueue_scripts';

    /**
     * Add a CSS asset to the theme
     * @param string       $handle
     * @param array|string $options
     * @return self
     */
    public function addThemeCss(string $handle, array|string $options): self
    {
        return $this->add(static::HOOK_THEME, 'css', $handle, $options);
    }

    /**
     * Add a JavaScript asset to the theme
     * @param string       $handle
     * @param array|string $options
     * @return self
     */
    public function addThemeJs(string $handle, array|string $options): self
    {
        return $this->add(static::HOOK_THEME, 'js', $handle, $options);
    }

    /**
     * Add a CSS asset to the admin panel
     * @param string       $handle
     * @param array|string $options
     * @return self
     */
    public function addAdminCss(string $handle, array|string $options): self
    {
        return $this->add(static::HOOK_ADMIN, 'css', $handle, $options);
    }

    /**
     * Add a JavaScript asset to the admin panel
     * @param string       $handle
     * @param array|string $options
     * @return self
     */
    public function addAdminJs(string $handle, array|string $options): self
    {
        return $this->add(static::HOOK_ADMIN, 'js', $handle, $options);
    }

    /**
     * Add a CSS asset to the login screen
     * @param string       $handle
     * @param array|string $options
     * @return self
     */
    public function addLoginCss(string $handle, array|string $options): self
    {
        return $this->add(static::HOOK_LOGIN, 'css', $handle, $options);
    }

    /**
     * Add a JavaScript asset to the login screen
     * @param string       $handle
     * @param array|string $options
     * @return self
     */
    public function addLoginJs(string $handle, array|string $options): self
    {
        return $this->add(static::HOOK_LOGIN, 'js', $handle, $options);
    }

    /**
     * Add a CSS or JavaScript asset
     * @param string       $hook      The hook to register the asset in
     * @param string       $assetType The type of asset, 'css' or 'js'
     * @param string       $handle
     * @param array|string $options
     * @return self
     */
    protected function add(string $hook, string $assetType, string $handle, array|string $options): self
    {
        add_action($hook, function () use ($assetType, $handle, $options) {
            $options = $this->parseOptions($assetType, $options);

            // Register the asset
            match ($assetType) {
                'css' => wp_enqueue_style($handle, $options['uri'], $options['deps'], $options['ver'], $options['media']),
                'js' => wp_enqueue_script($handle, $options['uri'], $options['deps'], $options['ver'], $options['footer']),
            };
        });

        return $this;
    }

    /**
     * Parse asset options for registering
     * @param string       $assetType The type of asset, 'css' or 'js'
     * @param array|string $options   Either an options array, or only a URI
     * @return array
     */
    protected function parseOptions(string $assetType, array|string $options): array
    {
        // Convert to options array, if only a URI is given
        if (is_string($options)) {
            $options = ['uri' => $options];
        }

        // Default values for CSS and JavaScript
        $default = [
            'deps' => [],
            'ver' => null,
        ];

        // Merge the options with the default
        return match ($assetType) {
            'css' => array_replace($default, ['media' => 'all'], $options),
            'js' => array_replace($default, ['footer' => true], $options),
            default => [],
        };
    }
}
