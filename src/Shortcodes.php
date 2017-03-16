<?php

namespace Wordclass;

class Shortcodes {
    /**
     * [base_url]
     * Get the base URL of the website, with trailing slash
     * @return String
     */
    public static function baseUrl() {
        static::add('base_url', function() {
            return rtrim(esc_url(home_url()), '/') . '/';
        });
    }



    /**
     * [copyright year='2016']
     * 'year' is optional, defaults to current
     * Creates a '© 2013 - 2016 Site name' line
     * @return String
     */
    public static function copyright() {
        static::add('copyright', function($params) {
            $currentYear = date('Y');
            $params = shortcode_atts(['year' => $currentYear], $params);

            $years = $params['year'];
            ((int) $params['year'] < $currentYear)  &&  $years .= ' - '.$currentYear;

            return '&copy; ' . $years . ' ' . get_bloginfo('name');
        });
    }



    /**
     * Simple wrapper function, for consistency
     * @param String    $name      Shortcode tag name
     * @param Callable  $callback  Rendering function
     */
    public function add($name, $callback) {
        add_shortcode($name, $callback);
    }
}
