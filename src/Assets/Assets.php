<?php

namespace Nerbiz\WordClass\Assets;

class Assets
{
    /**
     * Add CSS asset to the theme
     * @param string       $handle
     * @param array|string $options
     * @return self
     */
    public function addThemeCss(string $handle, array|string $options): self
    {
        return $this->add('css', 'wp_enqueue_scripts', $handle, $options);
    }

    /**
     * Add JavaScript asset to the theme
     * @param string       $handle
     * @param array|string $options
     * @return self
     */
    public function addThemeJs(string $handle, array|string $options): self
    {
        return $this->add('js', 'wp_enqueue_scripts', $handle, $options);
    }

    /**
     * Add CSS asset to admin
     * @param string       $handle
     * @param array|string $options
     * @return self
     */
    public function addAdminCss(string $handle, array|string $options): self
    {
        return $this->add('css', 'admin_enqueue_scripts', $handle, $options);
    }

    /**
     * Add JavaScript asset to admin
     * @param string       $handle
     * @param array|string $options
     * @return self
     */
    public function addAdminJs(string $handle, array|string $options): self
    {
        return $this->add('js', 'admin_enqueue_scripts', $handle, $options);
    }

    /**
     * Add CSS asset to the login screen
     * @param string       $handle
     * @param array|string $options
     * @return self
     */
    public function addLoginCss(string $handle, array|string $options): self
    {
        return $this->add('css', 'login_enqueue_scripts', $handle, $options);
    }

    /**
     * Add JavaScript asset to the login screen
     * @param string       $handle
     * @param array|string $options
     * @return self
     */
    public function addLoginJs(string $handle, array|string $options): self
    {
        return $this->add('js', 'login_enqueue_scripts', $handle, $options);
    }

    /**
     * Add a CSS or JavaSscript asset
     * @param string       $assetType The type of asset, 'css' or 'js'
     * @param string       $hook      The hook to register the asset in
     * @param string       $handle
     * @param array|string $options
     * @return self
     * @see Assets::parseOptions()
     */
    protected function add(string $assetType, string $hook, string $handle, array|string $options): self
    {
        add_action($hook, function () use ($assetType, $handle, $options) {
            $options = $this->parseOptions($assetType, $options);

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
    protected function parseOptions(string $assetType, array|string $options): array
    {
        // Convert to options array, if only a URI is given
        if (is_string($options)) {
            $options = ['uri' => $options];
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
}
