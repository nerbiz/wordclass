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

    /**
     * Disable XML-RPC functionality
     * @return self
     */
    public function disableXmlRpc(): self
    {
        // Replace the normal class with a dummy
        add_filter('wp_xmlrpc_server_class', function () {
            require_once dirname(__FILE__, 2) . '/includes/php/BrokenXmlRpcServer.php';
            return BrokenXmlRpcServer::class;
        });

        // For completeness, disable it and remove methods
        add_filter('xmlrpc_enabled', '__return_false');
        add_filter('xmlrpc_methods', '__return_empty_array');

        return $this;
    }
}
