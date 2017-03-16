<?php

namespace Wordclass\Traits;

trait CanSetTextDomain {
    /**
     * The text domain for translating the theme (static and on instance)
     * @var String
     */
    private static $_textDomainStatic = 'text_domain';
    private $_textDomain = 'text_domain';



    /**
     * Set the text domain for translating (statically)
     * @param  String  $domain
     */
    public static function setTextDomain($domain) {
        static::$_textDomainStatic = $domain;
    }


    /**
     * Set the text domain for translating (on instance)
     * @param  String  $domain
     * @return $this
     */
    public function textDomain($domain) {
        $this->_textDomain = $domain;

        return $this;
    }
}
