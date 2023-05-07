<?php

namespace Nerbiz\WordClass;

class Security
{
    /**
     * Remove the meta[name="generator"] tag
     * @return self
     */
    public function removeGeneratorMeta(): self
    {
        remove_action('wp_head', 'wp_generator');

        return $this;
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
                    '/(?<=[?&]ver=)(?<version>[^&]+)/i',
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
