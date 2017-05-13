<?php

namespace Wordclass\Traits;

use Wordclass\Init;

trait CanSetTextDomain {
    /**
     * The text domain for translations
     * @var String
     */
    private static $_textDomain = null;



    /**
     * Set or get the text domain for translating
     * @param  String  $domain
     * @return String
     */
    public static function textDomain($domain=null) {
        if($domain)
            static::$_textDomain = $domain;

        else {
            // A class-specific value takes precedence
            if(static::$_textDomain !== null)
                return static::$_textDomain;

            else {
                $default = Init::defaultTextDomain();
                // Use the default value in Init if it is set
                if($default !== null)
                    return $default;
                // Fallback value
                else
                    return 'text-domain';
            }
        }
    }
}
