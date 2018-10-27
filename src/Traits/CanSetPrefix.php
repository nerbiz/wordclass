<?php

namespace Nerbiz\Wordclass\Traits;

use Nerbiz\Wordclass\Init;

trait CanSetPrefix
{
    /**
     * The prefix
     * @var String
     */
    protected static $prefix = null;

    /**
     * Set or get the prefix
     * @param  string  $prefix
     * @return string
     */
    public static function prefix($prefix = null)
    {
        if ($prefix) {
            static::$prefix = $prefix;
        } else {
            // A class-specific value takes precedence
            if (static::$prefix !== null) {
                return static::$prefix;
            } else {
                // Use the default value in Init if it is set
                $default = Init::defaultPrefix();
                if ($default !== null) {
                    return $default;
                }

                // Fallback value
                else {
                    return 'wc';
                }
            }
        }
    }
}
