<?php

namespace Wordclass;

class Plugins {
    private $_id;
    private $_includePlugins = false;
    private $_config = false;



    public function __construct($id) {
        $this->_id = $id;
    }



    /**
     * Set the config for TGMPA
     * @param   Array  $config  Values that will overwrite the defaults
     * @return  $this
     */
    public function config($config=[]) {
        $config = (array) $config;

        $this->_config = array_replace_recursive([
            // Unique ID for hashing notices for multiple instances of TGMPA.
            'id'           => $this->_id,
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

        return $this;
    }



    /**
     * Set the required/recommended plugins for the theme, in slug:options pairs
     * @param   Array       $plugins  An array of slug:options pairs
     *                                  Can be a string (slug), but then the 2nd parameter is required
     * @param   Array|null  $options  If $plugins is a string (slug), these are the options for it
     * @return  $this
     */
    public function include($plugins, $options=null) {
        $this->_includePlugins = [];

        if(is_string($plugins))
            $plugins = [$plugins => $options];

        foreach($plugins as $slug => $options) {
            $options['slug'] = $slug;
            $this->_includePlugins[] = $options;
        }

        return $this;
    }



    /**
     * Apply the plugins and settings
     */
    public function set() {
        if($this->_includePlugins) {
            if( ! $this->_config)
                $this->config();

            add_action('tgmpa_register', function() {
                tgmpa($this->_includePlugins, $this->_config);
            });
        }
    }



    /**
     * Create a new TGMPA instance
     * @return Object  An instance of this class
     */
    public static function init() {
        return new static(uniqid('', true));
    }
}
