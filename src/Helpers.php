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
     * html: an 'img' HTML tag
     * @return array|string
     * @throws InvalidArgumentException
     */
    public function getImage(
        int $imageId,
        string $sizeName = 'large',
        string $returnType = 'url'
    ): array|string {
        return match ($returnType) {
            'url' => wp_get_attachment_image_url($imageId, $sizeName),
            'array' => wp_get_attachment_image_src($imageId, $sizeName),
            'html' => wp_get_attachment_image($imageId, $sizeName),
            default => throw new InvalidArgumentException(sprintf(
                "%s() expects parameter 'returnType' to be 'url', 'array' or 'html', '%s' given",
                __METHOD__,
                is_object($returnType) ? get_class($returnType) : gettype($returnType)
            )),
        };
    }

    /**
     * Get a featured image from a post
     * @param  int    $postId
     * @param  string $sizeName
     * @param  string $returnType
     * @return array|string
     * @see self::getImage()
     */
    public function getFeaturedImage(
        int $postId,
        string $sizeName = 'large',
        string $returnType = 'url'
    ): array|string {
        $imageId = get_post_thumbnail_id($postId);

        return $this->getImage((int)$imageId, $sizeName, $returnType);
    }

    /**
     * Get an image from an option name
     * @param string $optionName
     * @param string $sizeName
     * @param string $returnType
     * @return array|string
     * @see self::getImage()
     */
    public function getOptionImage(
        string $optionName,
        string $sizeName = 'large',
        string $returnType = 'url'
    ): array|string {
        $imageId = (new Options())->get($optionName);

        return $this->getImage((int)$imageId, $sizeName, $returnType);
    }
}
