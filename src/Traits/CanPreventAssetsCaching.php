<?php

namespace Nerbiz\Wordclass\Traits;

trait CanPreventAssetsCaching
{
    /**
     * This string is appended to all asset URLs
     * @var String
     */
    protected static $assetAppend = '';

    /**
     * Whether or not to prevent assets browser caching
     * @param  bool  $prevent
     */
    public static function preventCache($prevent)
    {
        static::$assetAppend = ($prevent)
            ? '?v=' . time()
            : '';
    }
}
