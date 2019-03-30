<?php

namespace Nerbiz\Wordclass;

class Plugins
{
    /**
     * @var Utilities
     */
    protected $utilities;

    /**
     * The TGMPA configuration options
     * @var array
     */
    protected $config = [];

    public function __construct()
    {
        $this->utilities = new Utilities();

        $this->setConfig([
            // Unique ID for hashing notices for multiple instances of TGMPA
            'id'           => uniqid('', true),
            // Default absolute path to bundled plugins
            'default_path' => '',
            // Menu slug
            'menu'         => 'tgmpa-install-plugins',
            // Parent menu slug
            'parent_slug'  => 'themes.php',
            // Capability needed to view plugin install page, should be a capability associated with the parent menu used
            'capability'   => 'edit_theme_options',
            // Whether to show admin notices
            'has_notices'  => true,
            // If false, a user cannot dismiss the nag message
            'dismissable'  => true,
            // If 'dismissable' is false, this message will be output at top of nag
            'dismiss_msg'  => '',
            // Automatically activate plugins after installation or not
            'is_automatic' => false,
            // Message to output right before the plugins table
            'message'      => ''
        ]);
    }

    /**
     * Set the config for TGMPA
     * @param  array $config Values that will overwrite the defaults
     * @return self
     */
    public function setConfig(array $config = []): self
    {
        $this->config = array_replace($this->config, $config);

        return $this;
    }

    /**
     * Set the required/recommended plugins for the theme, in name:options pairs
     * @param  array $plugins An array of name:options pairs
     *   If the 'slug' option is omitted, it will be derived from the name
     * Options:
     * name: The plugin name
     * slug: The plugin slug (typically the folder name)
     * source: The plugin source
     * required: If false, the plugin is only 'recommended' instead of required
     * version: E.g. 1.0.0. If set, the active plugin must be this version or higher.
     *   If the plugin version is higher than the plugin version installed,
     *   the user will be notified to update the plugin
     * force_activation: If true, plugin is activated upon theme activation
     *   and cannot be deactivated until theme switch
     * force_deactivation: If true, plugin is deactivated upon theme switch,
     *   useful for theme-specific plugins
     * external_url: If set, overrides default API URL and points to an external URL
     * is_callable: If set, this callable will be be checked for availability
     *   to determine if a plugin is active
     * @return self
     */
    public function include(array $plugins): self
    {
        add_action('tgmpa_register', function () use ($plugins) {
            $includePlugins = [];

            foreach ($plugins as $name => $options) {
                // In case 'options' is a string, an array of names has been given, without options
                if (is_string($options)) {
                    $name = $options;
                    $options = [];
                }

                $options['name'] = $name;

                // Derive the slug from the name, if not given
                if (! isset($options['slug'])) {
                    $options['slug'] = $this->utilities->createSlug($name);
                }

                $includePlugins[] = $options;
            }

            tgmpa($includePlugins, $this->config);
        });

        return $this;
    }
}
