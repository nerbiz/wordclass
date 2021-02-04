<?php

namespace Nerbiz\WordClass;

use InvalidArgumentException;

class Helpers
{
    /**
     * Get the URL of a featured image
     * @param  int    $imageId    The ID of the image
     * @param  string $sizeName   The name of one of the regisered image sizes
     * @param  string $returnType The type of data to return
     * Return types:
     * url: the image URL
     * array: [url, width, height, is_intermediate]
     * html: an <img> element
     * @return array|string
     * @throws InvalidArgumentException
     */
    public function getImage(int $imageId, string $sizeName = 'large', string $returnType = 'url')
    {
        switch ($returnType) {
            case 'url':
                return wp_get_attachment_image_url($imageId, $sizeName);
            case 'array':
                return wp_get_attachment_image_src($imageId, $sizeName);
            case 'html':
                return wp_get_attachment_image($imageId, $sizeName);
            default:
                throw new InvalidArgumentException(sprintf(
                    "%s() expects parameter 'returnType' to be 'url', 'array' or 'html', '%s' given",
                    __METHOD__,
                    is_object($returnType) ? get_class($returnType) : gettype($returnType)
                ));
        }
    }

    /**
     * Wrapper for getImage(), using post featured image
     * @param  int    $postId
     * @param  string $sizeName
     * @param  string $returnType
     * @return array|string
     * @see Helpers::getImage()
     */
    public function getFeaturedImage(int $postId, string $sizeName = 'large', string $returnType = 'url')
    {
        $imageId = get_post_thumbnail_id($postId);

        return $this->getImage((int)$imageId, $sizeName, $returnType);
    }

    /**
     * Get an image from an option name
     * @param string $optionName
     * @param string $sizeName
     * @param string $returnType
     * @return array|string
     * @see Helpers::getImage()
     */
    public function getOptionImage(string $optionName, string $sizeName = 'large', string $returnType = 'url')
    {
        $imageId = (new Options())->get($optionName);

        return $this->getImage((int)$imageId, $sizeName, $returnType);
    }
}
