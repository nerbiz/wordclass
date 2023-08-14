<?php

namespace Nerbiz\WordClass;

use InvalidArgumentException;

class Helpers
{
    /**
     * Prepend a value with the current prefix in the Init class
     * @param string $value
     * @param string $separator Character(s) between prefix and value
     * @return string
     * @see Init::getPrefix()
     */
    public static function withPrefix(string $value, string $separator = '_'): string
    {
        return Init::getPrefix() . $separator . $value;
    }

    /**
     * Get the URL of a featured image
     * @param  int    $attachmentId The ID of the image
     * @param  string $sizeName     The name of one of the regisered image sizes
     * @param  string $returnType   The type of data to return
     * Return types:
     * 'url': the image URL
     * 'array': [url, width, height, is_resized]
     * 'html': an 'img' HTML tag
     * @return array|string
     * @throws InvalidArgumentException
     */
    public static function getImage(
        int    $attachmentId,
        string $sizeName = 'large',
        string $returnType = 'url'
    ): array|string {
        return match ($returnType) {
            'url' => wp_get_attachment_image_url($attachmentId, $sizeName),
            'array' => wp_get_attachment_image_src($attachmentId, $sizeName),
            'html' => wp_get_attachment_image($attachmentId, $sizeName),
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
    public static function getFeaturedImage(
        int    $postId,
        string $sizeName = 'large',
        string $returnType = 'url'
    ): array|string {
        $attachmentId = get_post_thumbnail_id($postId);

        return static::getImage((int)$attachmentId, $sizeName, $returnType);
    }

    /**
     * Get an image from an option name
     * @param string $optionName
     * @param string $sizeName
     * @param string $returnType
     * @return array|string
     * @see self::getImage()
     */
    public static function getOptionImage(
        string $optionName,
        string $sizeName = 'large',
        string $returnType = 'url'
    ): array|string {
        $attachmentId = Options::get($optionName);

        return static::getImage((int)$attachmentId, $sizeName, $returnType);
    }
}
