<?php

namespace Nerbiz\Wordclass;

class Plugins
{
    /**
     * The TGMPA configuration options
     * @var array
     */
    protected $config = [];

    /**
     * Set the config for TGMPA
     * @param  array $config Values that will overwrite the defaults
     * @return self
     */
    public function setConfig(array $config): self
    {
        $this->config = array_replace($this->config, $config);

        return $this;
    }

    /**
     * Set the required/recommended plugins for the theme, in name:options pairs
     * @param  array $plugins An array of name:options pairs
     * If the 'slug' option is omitted, it will be derived from the name
     * @see http://tgmpluginactivation.com/configuration/
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
                    $options['slug'] = Utilities::createSlug($name);
                }

                $includePlugins[] = $options;
            }

            tgmpa($includePlugins, $this->config);
        });

        return $this;
    }
}
