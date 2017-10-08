<?php
/**
 * Convenience functions for the custom translation methods
 * Those methods support a variable for the text domain
 */

/**
 * Returning functions
 */
if( ! function_exists('_translate')) {
    function _translate($text, $domain) {
        return Wordclass\Traits\CanTranslate::translate($text, $domain);
    }
}

if( ! function_exists('___')) {
    function ___($text, $domain) {
        return Wordclass\Traits\CanTranslate::__($text, $domain);
    }
}

if( ! function_exists('__n')) {
    function __n($single, $plural, $number, $domain) {
        return Wordclass\Traits\CanTranslate::_n($single, $plural, $number, $domain);
    }
}

if( ! function_exists('__x')) {
    function __x($text, $context, $domain) {
        return Wordclass\Traits\CanTranslate::_x($text, $context, $domain);
    }
}

if( ! function_exists('__nx')) {
    function __nx($single, $plural, $number, $context, $domain) {
        return Wordclass\Traits\CanTranslate::_nx($single, $plural, $number, $context, $domain);
    }
}

if( ! function_exists('_esc_attr__')) {
    function _esc_attr__($text, $domain) {
        return Wordclass\Traits\CanTranslate::esc_attr__($text, $domain);
    }
}

if( ! function_exists('_esc_attr_x')) {
    function _esc_attr_x($text, $context, $domain) {
        return Wordclass\Traits\CanTranslate::esc_attr_x($text, $context, $domain);
    }
}

if( ! function_exists('_esc_html__')) {
    function _esc_html__($text, $domain) {
        return Wordclass\Traits\CanTranslate::esc_html__($text, $domain);
    }
}

if( ! function_exists('_esc_html_x')) {
    function _esc_html_x($text, $context, $domain) {
        return Wordclass\Traits\CanTranslate::esc_html_x($text, $context, $domain);
    }
}

if( ! function_exists('__n_noop')) {
    function __n_noop($single, $plural, $domain) {
        return Wordclass\Traits\CanTranslate::_n_noop($single, $plural, $domain);
    }
}

if( ! function_exists('__nx_noop')) {
    function __nx_noop($single, $plural, $context, $domain) {
        return Wordclass\Traits\CanTranslate::_nx_noop($single, $plural, $context, $domain);
    }
}

if( ! function_exists('_translate_nooped_plural')) {
    function _translate_nooped_plural($nooped_plural, $count, $domain) {
        return Wordclass\Traits\CanTranslate::translate_nooped_plural($nooped_plural, $count, $domain);
    }
}



/**
 * Echo'ing functions
 */
if( ! function_exists('__e')) {
    function __e($text, $domain) {
        Wordclass\Traits\CanTranslate::_e($text, $domain);
    }
}

if( ! function_exists('__ex')) {
    function __ex($text, $context, $domain) {
        Wordclass\Traits\CanTranslate::_ex($text, $context, $domain);
    }
}

if( ! function_exists('_esc_attr_e')) {
    function _esc_attr_e($text, $domain) {
        Wordclass\Traits\CanTranslate::esc_attr_e($text, $domain);
    }
}

if( ! function_exists('_esc_html_e')) {
    function _esc_html_e($text, $domain) {
        Wordclass\Traits\CanTranslate::esc_html_e($text, $domain);
    }
}
