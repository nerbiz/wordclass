<?php

use Nerbiz\Wordclass\Helpers;
use Nerbiz\Wordclass\Pool;

if (! function_exists('nw_get_helpers')) {
    /**
     * Get a reusable helpers object
     * @return Helpers
     * @throws Exception
     */
    function nw_get_helpers(): Helpers
    {
        if (! Pool::has('nw_helpers')) {
            Pool::set('nw_helpers', new Helpers());
        }

        return Pool::get('nw_helpers');
    }
}

if (! function_exists('nw_get_image')) {
    /**
     * @param int    $imageId
     * @param string $sizeName
     * @param string $returnType
     * @return array|string
     * @throws Exception
     * @see Helpers::getImage()
     */
    function nw_get_image(int $imageId, string $sizeName = 'large', string $returnType = 'url')
    {
        return nw_get_helpers()->getImage($imageId, $sizeName, $returnType);
    }
}

if (! function_exists('nw_get_featured_image')) {
    /**
     * @param int    $postId
     * @param string $sizeName
     * @param string $returnType
     * @return array|string
     * @throws Exception
     * @see Helpers::getFeaturedImage()
     */
    function nw_get_featured_image(int $postId, string $sizeName = 'large', string $returnType = 'url')
    {
        return nw_get_helpers()->getFeaturedImage($postId, $sizeName, $returnType);
    }
}

if (! function_exists('nw_get_option')) {
    /**
     * @param string $name
     * @return string|null
     * @throws Exception
     * @see Helpers::getOption()
     */
    function nw_get_option(string $name): ?string
    {
        return nw_get_helpers()->getOption($name);
    }
}
