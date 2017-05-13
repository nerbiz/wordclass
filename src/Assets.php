<?php

namespace Wordclass;

class Assets {
    use Traits\CanPreventAssetsCaching;



    /**
     * Add assets
     * @param  String        $for     'theme' / 'admin' / 'login'
     * @param  String        $type    'css' or 'js'
     * @param  Array|String  $assets  The assets as handle:options pairs
     *                                  path: relative path to the file
     *                                  after: the assets that have to load before this one (default: none)
     *                                  For css
     *                                    media: the 'media' attribute of the style tag (default: 'all')
     *                                  For js
     *                                    footer: add this script to the header (false) or the footer (true) (default: true)
     *                                  Instead of an options array, options can be a path string, using default options
     * @param  String        $path    (Only used when $assets is a string) shorthand for registering 1 style, with default options
     */
    private static function addAsset($for, $type, $assets, $path) {
        // Decide which action hook to use, based on where the asset needs to go
        ($for == 'theme')  &&  $for = 'wp';
        $actionHook = $for.'_enqueue_scripts';

        // Only CSS and JS are supported
        if(in_array($type, ['css', 'js'])) {
            $defaultCssOptions = [
                'after' => [],
                'media' => 'all'
            ];

            $defaultJsOptions = [
                'after' => [],
                'footer' => true
            ];

            // Decide which default options to use
            $defaultOptions = ${'default' . ucfirst($type) . 'Options'};

            add_action($actionHook, function() use($type, $assets, $path, $defaultOptions) {
                $urlRegEx = '~^(https?:)?//';

                // Shorthand, when 1 asset is given ($assets and $path are strings)
                if(is_string($assets)  &&  is_string($path)) {
                    $handle = $assets;
                    $options = array_replace($defaultOptions, [
                        'path' => preg_match($urlRegEx, $path)
                                    ? $path
                                    : get_stylesheet_directory_uri() . '/' . $path . static::$_assetAppend
                    ]);

                    if($type == 'css')
                        wp_enqueue_style($handle, $options['path'], $options['after'], null, $options['media']);
                    else if($type == 'js')
                        wp_enqueue_script($handle, $options['path'], $options['after'], null, $options['footer']);
                }

                else if(is_array($assets)) {
                    foreach($assets as $handle => $options) {
                        // Shorthand, when only a path is given
                        if(is_string($options))
                            $options = ['path' => $options];

                        // Create the full path
                        $options['path'] = preg_match($urlRegEx, $path) ?
                                            $path
                                            : get_stylesheet_directory_uri() . '/' . $path . static::$_assetAppend;

                        // Merge options with the defaults
                        $options = array_replace($defaultOptions, $options);

                        if($type == 'css')
                            wp_enqueue_style($handle, $options['path'], $options['after'], null, $options['media']);
                        else if($type == 'js')
                            wp_enqueue_script($handle, $options['path'], $options['after'], null, $options['footer']);
                    }
                }
            });
        }
    }



    /**
     * Wrapper functions for convenience
     */
    public static function add($type, $assets, $path='') {
        static::addAsset('theme', $type, $assets, $path);
    }

    public static function addAdmin($type, $assets, $path='') {
        static::addAsset('admin', $type, $assets, $path);
    }

    public static function addLogin($type, $assets, $path='') {
        static::addAsset('login', $type, $assets, $path);
    }
}
