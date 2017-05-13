<?php

namespace Wordclass;

class Plugins {
    private static $_config = [];



    /**
     * Set the config for TGMPA
     * @param   Array  $config  Values that will overwrite the defaults
     */
    public static function config($config=[]) {
        $config = (array) $config;

        static::$_config = array_replace_recursive([
            // Unique ID for hashing notices for multiple instances of TGMPA.
            'id'           => uniqid('', true),
            // Default absolute path to bundled plugins.
            'default_path' => '',
            // Menu slug.
            'menu'         => 'tgmpa-install-plugins',
            // Parent menu slug.
            'parent_slug'  => 'themes.php',
            // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'capability'   => 'edit_theme_options',
            // Show admin notices or not.
            'has_notices'  => true,
            // If false, a user cannot dismiss the nag message.
            'dismissable'  => true,
            // If 'dismissable' is false, this message will be output at top of nag.
            'dismiss_msg'  => '',
            // Automatically activate plugins after installation or not.
            'is_automatic' => false,
            // Message to output right before the plugins table.
            'message'      => ''
        ], $config);
    }



    /**
     * Set the required/recommended plugins for the theme, in name:options pairs
     * @param   Array       $plugins  An array of name:options pairs
     *                                  Can be a string (name), but then the 2nd parameter is required
     * @param   Array|null  $options  If $plugins is a string, these are the options for it
     * Valid calls:
     *   include('Plugin One')
     *   include(['Plugin One', 'Plugin Two'])
     *   include('Plugin One' => []), with an array of options, which can be empty
     *   include([
     *       'Plugin One' => [],
     *       'Plugin Two' => []
     *   )
     */
    public static function include($plugins, $options=[]) {
        if(is_string($plugins))
            $plugins = [$plugins => $options];

        // Use the default config, if it's not set yet
        if(empty(static::$_config))
            static::config();

        add_action('tgmpa_register', function() use($plugins) {
            $includePlugins = [];

            foreach($plugins as $name => $options) {
                // In case the 'options' are a string (not an options array)
                // An array of names has been given, without options
                if(is_string($options)) {
                    $name = $options;
                    $options = [];
                }

                $options['name'] = $name;
                // Derive the slug from the name, if not given
                if( ! isset($options['slug']))
                    $options['slug'] = Utilities::createSlug($name);

                $includePlugins[] = $options;
            }

            tgmpa($includePlugins, static::$_config);
        });
    }
}
