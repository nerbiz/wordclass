<?php

namespace Wordclass;

class Fixes {
    /**
     * Should fix: 'Unable to locate WordPress plugin directory.'
     * Set the filesystem method to 'direct'
     * define('FS_METHOD', 'direct') didn't work to solve this
     */
    public static function cantLocatePluginDirectory() {
        if(is_admin()) {
            add_filter('filesystem_method', function($method) {
                return 'direct';
            });
        }
    }



    /**
     * Should fix: TinyMCE dropdowns (like format-select) not showing
     * When TinyMCE is loaded in a modal dialog, the dropdowns are behind the modal dialog, so the z-index fixes that
     */
    public static function hiddenEditorDropdowns() {
        add_action('admin_enqueue_scripts', function() {
            $css = file_get_contents(__DIR__ . '/../includes/css/hidden-editor-dropdowns.css');
            echo '<style>' . $css . '</style>';
        });
    }
}
