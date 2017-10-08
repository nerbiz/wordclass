<?php

namespace Wordclass\Traits;

/**
 * All the methods in this trait are wrappers for the equally named Wordpress functions
 * The difference is that these methods do accept a variable for the text domain
 */
trait CanTranslate {
    /**
     * Returning methods
     */
    public static function translate($text, $domain) {
        return translate($text, var_export($domain, true));
    }

    public static function __($text, $domain) {
        return __($text, var_export($domain, true));
    }

    public static function _n($single, $plural, $number, $domain) {
        return _n($single, $plural, $number, var_export($domain, true));
    }

    public static function _x($text, $context, $domain) {
        return _x($text, $context, var_export($domain, true));
    }

    public static function _nx($single, $plural, $number, $context, $domain) {
        _nx($single, $plural, $number, $context, var_export($domain, true));
    }

    public static function esc_attr__($text, $domain) {
        return esc_attr__($text, var_export($domain, true));
    }

    public static function esc_attr_x($text, $context, $domain) {
        return esc_attr_x($text, $context, var_export($domain, true));
    }

    public static function esc_html__($text, $domain) {
        return esc_html__($text, var_export($domain, true));
    }

    public static function esc_html_x($text, $context, $domain) {
        esc_html_x($text, $context, var_export($domain, true));
    }

    public static function _n_noop($single, $plural, $domain) {
        return _n_noop($single, $plural, var_export($domain, true));
    }

    public static function _nx_noop($single, $plural, $context, $domain) {
        _nx_noop($single, $plural, $context, var_export($domain, true));
    }

    public static function translate_nooped_plural($nooped_plural, $count, $domain) {
        translate_nooped_plural($nooped_plural, $count, var_export($domain, true));
    }



    /**
     * Echo'ing methods
     */
    public static function _e($text, $domain) {
        _e($text, var_export($domain, true));
    }

    public static function _ex($text, $context, $domain) {
        _ex($text, $context, var_export($domain, true));
    }

    public static function esc_attr_e($text, $domain) {
        esc_attr_e($text, var_export($domain, true));
    }

    public static function esc_html_e($text, $domain) {
        esc_html_e($text, var_export($domain, true));
    }
}
