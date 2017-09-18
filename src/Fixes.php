<?php

namespace Wordclass;

class Fixes {
    /**
     * Set the filesystem method to 'direct'
     * Should fix: 'Unable to locate WordPress plugin directory.'
     * define('FS_METHOD', 'direct') didn't work to solve this
     */
    public static function cantLocatePluginDirectory() {
        if(is_admin()) {
            add_filter('filesystem_method', function($method) {
                return 'direct';
            });
        }
    }
}
