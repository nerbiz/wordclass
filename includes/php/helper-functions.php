<?php

use Nerbiz\Wordclass\Factory;
use Nerbiz\Wordclass\Helpers;

/**
 * @see Helpers::getImage()
 */
if (! function_exists('nw_get_image')) {
    function nw_get_image(int $imageId, string $sizeName = 'large', string $returnType = 'url')
    {
        $helpers = Factory::reuse('Helpers');
        return $helpers->getImage($imageId, $sizeName, $returnType);
    }
}

/**
 * @see Helpers::getFeaturedImage()
 */
if (! function_exists('nw_get_featured_image')) {
    function nw_get_featured_image(int $postId, string $sizeName = 'large', string $returnType = 'url')
    {
        $helpers = Factory::reuse('Helpers');
        return $helpers->getFeaturedImage($postId, $sizeName, $returnType);
    }
}

/**
 * @see Helpers::getOption()
 */
if (! function_exists('nw_get_option')) {
    function nw_get_option(string $name): ?string
    {
        $helpers = Factory::reuse('Helpers');
        return $helpers->getOption($name);
    }
}
