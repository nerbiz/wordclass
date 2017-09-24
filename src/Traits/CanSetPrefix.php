<?php

namespace Wordclass\Traits;

use Wordclass\Init;

trait CanSetPrefix {
    /**
     * The prefix
     * @var String
     */
    private static $_prefix = null;



    /**
     * Set or get the prefix
     * @param  String  $prefix
     * @return String
     */
    public static function prefix($prefix=null) {
        if($prefix)
            static::$_prefix = $prefix;

        else {
            // A class-specific value takes precedence
            if(static::$_prefix !== null)
                return static::$_prefix;

            else {
                // Use the default value in Init if it is set
                $default = Init::defaultPrefix();
                if($default !== null)
                    return $default;

                // Fallback value
                else
                    return 'wc';
            }
        }
    }
}
