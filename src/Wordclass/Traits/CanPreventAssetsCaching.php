<?php

namespace Wordclass\Traits;

trait CanPreventAssetsCaching {
    /**
     * This string is appended to all asset URLs
     * @var String
     */
    private static $_assetAppend = '';



    /**
     * Whether or not to prevent assets browser caching
     * @param  Boolean  $prevent
     */
    public static function preventCache($prevent) {
        static::$_assetAppend = ($prevent)  ?  '?v='.time()  :  '';
    }
}
