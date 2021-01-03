<?php

use Nerbiz\Wordclass\Helpers;
use Nerbiz\Wordclass\Options;
use Nerbiz\Wordclass\Pool;

Pool::set('nw_helpers', new Helpers());
Pool::set('nw_options', new Options());

if (! function_exists('nw_get_image')) {
    /**
     * @param int    $imageId
     * @param string $sizeName
     * @param string $returnType
     * @return array|string
     * @see Helpers::getImage()
     */
    function nw_get_image(int $imageId, string $sizeName = 'large', string $returnType = 'url')
    {
        try {
            return Pool::get('nw_helpers')->getImage($imageId, $sizeName, $returnType);
        } catch (Exception $exception) {
            // The try will always succeed, because Pool::set() is called in this file
        }
    }
}

if (! function_exists('nw_get_featured_image')) {
    /**
     * @param int    $postId
     * @param string $sizeName
     * @param string $returnType
     * @return array|string
     * @see Helpers::getFeaturedImage()
     */
    function nw_get_featured_image(int $postId, string $sizeName = 'large', string $returnType = 'url')
    {
        try {
            return Pool::get('nw_helpers')->getFeaturedImage($postId, $sizeName, $returnType);
        } catch (Exception $exception) {
            // The try will always succeed, because Pool::set() is called in this file
        }
    }
}

if (! function_exists('nw_get_option_image')) {
    /**
     * @param string $optionName
     * @param string $sizeName
     * @param string $returnType
     * @return array_string
     * @see Helpers::getOptionImage()
     */
    function nw_get_option_image(string $optionName, string $sizeName = 'large', string $returnType = 'url')
    {
        try {
            return Pool::get('nw_helpers')->getOptionImage($optionName, $sizeName, $returnType);
        } catch (Exception $exception) {
            // The try will always succeed, because Pool::set() is called in this file
        }
    }
}

if (! function_exists('nw_get_option')) {
    /**
     * @param string $name
     * @param null   $default
     * @return mixed
     * @see Options::get()
     */
    function nw_get_option(string $name, $default = null)
    {
        try {
            return Pool::get('nw_options')->get($name, $default);
        } catch (Exception $exception) {
            // The try will always succeed, because Pool::set() is called in this file
        }
    }
}
