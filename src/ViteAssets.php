<?php

namespace Nerbiz\WordClass;

use stdClass;

class ViteAssets extends Assets
{
    /**
     * The name of the compiled assets directory
     * @var string
     */
    protected string $distDirectory;

    /**
     * Parsed contents of the manifest file
     * @var stdClass
     */
    protected stdClass $manifest;

    /**
     * Whether the dev server is used
     * @var bool
     */
    protected bool $usingDevServer = false;

    /**
     * The host for the dev server
     * @var string
     */
    protected string $serverHost;

    /**
     * The port for the dev server
     * @var int
     */
    protected int $serverPort;

    /**
     * Whether to use https for the dev server
     * @var bool
     */
    protected bool $serverSecure = true;

    /**
     * Handles of scripts that should be modules (for dev server)
     * @var array
     */
    protected array $moduleHandles = [];

    /**
     * @param string $distDirectory The name of the compiled assets directory
     */
    public function __construct(string $distDirectory = 'dist')
    {
        $this->distDirectory = trim($distDirectory, '/');

        $manifestPath = sprintf(
            '%s/%s/manifest.json',
            get_stylesheet_directory(),
            $this->distDirectory
        );

        $this->manifest = is_readable($manifestPath)
            ? json_decode(file_get_contents($manifestPath))
            : new stdClass();
    }

    /**
     * @param string $host
     * @param int    $port
     * @param bool   $secure
     * @return self
     */
    public function useDevServer(string $host = '0.0.0.0', int $port = 5173, bool $secure = true): self
    {
        $this->usingDevServer = true;
        $this->serverHost = $host;
        $this->serverPort = $port;
        $this->serverSecure = $secure;

        // Add the dev server script
        add_action('wp_enqueue_scripts', function () {
            $serverUrl = sprintf(
                'http%s://%s:%d/@vite/client',
                $this->serverSecure ? 's' : '',
                $this->serverHost,
                $this->serverPort
            );

            wp_enqueue_script('vite-dev', $serverUrl, [], null, true);
            $this->moduleHandles[] = 'vite-dev';
        });

        // Set the type to 'module' for applicable scripts
        add_filter('script_loader_tag', function ($tag, $handle) {
            return (in_array($handle, $this->moduleHandles))
                ? str_replace('text/javascript', 'module', $tag)
                : $tag;
        }, 10, 2);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function add(string $assetType, string $hook, string $handle, $options): self
    {
        if ($this->usingDevServer === true && $assetType === 'js') {
            $this->moduleHandles[] = $handle;
        }

        return parent::add($assetType, $hook, $handle, $options);
    }

    /**
     * {@inheritdoc}
     */
    protected function parseOptions(string $assetType, $options): array
    {
        $options = parent::parseOptions($assetType, $options);
        $options['uri'] = ltrim($options['uri'], '/');

        if ($this->usingDevServer === true) {
            $options['uri'] = sprintf(
                'http%s://%s:%d/%s',
                $this->serverSecure ? 's' : '',
                $this->serverHost,
                $this->serverPort,
                $options['uri']
            );
        } else {
            if (isset($this->manifest->{$options['uri']}->file)) {
                $actualPath = $this->manifest->{$options['uri']}->file;
                $actualPath = '/' . ltrim($actualPath, '/');
                $options['uri'] = get_theme_file_uri($this->distDirectory . $actualPath);
            } else {
                $options['uri'] = '/' . $options['uri'];
            }
        }

        return $options;
    }
}
