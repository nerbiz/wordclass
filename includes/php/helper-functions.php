<?php

use Nerbiz\WordClass\Helpers;
use Nerbiz\WordClass\Options;
use Nerbiz\WordClass\Pool;

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
            /** @var Helpers $helpers */
            $helpers = Pool::get('nw_helpers');
            return $helpers->getImage($imageId, $sizeName, $returnType);
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
            /** @var Helpers $helpers */
            $helpers = Pool::get('nw_helpers');
            return $helpers->getFeaturedImage($postId, $sizeName, $returnType);
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
     * @return array|string
     * @see Helpers::getOptionImage()
     */
    function nw_get_option_image(string $optionName, string $sizeName = 'large', string $returnType = 'url')
    {
        try {
            /** @var Helpers $helpers */
            $helpers = Pool::get('nw_helpers');
            return $helpers->getOptionImage($optionName, $sizeName, $returnType);
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
            /** @var Options $options */
            $options = Pool::get('nw_options');
            return $options->get($name, $default);
        } catch (Exception $exception) {
            // The try will always succeed, because Pool::set() is called in this file
        }
    }
}
