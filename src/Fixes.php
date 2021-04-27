<?php

namespace Nerbiz\WordClass;

class Fixes
{
    /**
     * Should fix: 'Unable to locate WordPress plugin directory.'
     * Set the filesystem method to 'direct'
     * In case define('FS_METHOD', 'direct') didn't work to solve this
     * @return self
     */
    public function cantLocatePluginDirectory(): self
    {
        if (is_admin()) {
            add_filter('filesystem_method', function ($method) {
                return 'direct';
            });
        }

        return $this;
    }

    /**
     * Should fix: TinyMCE dropdowns (like format-select) not showing
     * When TinyMCE is loaded in a modal dialog, the dropdowns are behind the modal dialog, the z-index fixes that
     * @return self
     */
    public function hiddenEditorDropdowns(): self
    {
        add_action('admin_enqueue_scripts', function () {
            $css = file_get_contents(__DIR__ . '/../includes/css/hidden-editor-dropdowns.css');
            echo '<style>' . $css . '</style>' . PHP_EOL;
        });

        return $this;
    }
}
