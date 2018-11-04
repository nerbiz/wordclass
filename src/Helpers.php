<?php

namespace Nerbiz\Wordclass;

class Helpers
{
    /**
     * @var Init
     */
    protected $init;

    public function __construct()
    {
        $this->init = new Init();
    }

    /**
     * Get the URL of a featured image
     * @param  int    $imageId    The ID of the image
     * @param  string $sizeName   The name of one of the regisered image sizes
     * @param  string $returnType The type of data to return
     * Return types:
     * url: the image URL
     * array: [url, width, height, is_intermediate]
     * html: an <img> element
     * @return string|array
     */
    public function getImage($imageId, $sizeName = 'large', $returnType = 'url')
    {
        switch ($returnType) {
            case 'url':
                return wp_get_attachment_image_url($imageId, $sizeName);
                break;

            case 'array':
                return wp_get_attachment_image_src($imageId, $sizeName);
                break;

            case 'html':
                return wp_get_attachment_image($imageId, $sizeName);
                break;
            
            default:
                throw new \InvalidArgumentException(sprintf(
                    "%s() expects parameter 'returnType' to be 'url', 'array' or 'html', '%s' given",
                    __METHOD__,
                    is_object($returnType) ? get_class($returnType) : gettype($returnType)
                ));
                break;
        }
    }

    /**
     * Wrapper for getImage(), using post featured image
     * @param  int    $postId
     * @param  string $sizeName
     * @param  string $returnType
     * @return array|string
     * @see self::getImage()
     */
    public function getFeaturedImage($postId, $sizeName = 'large', $returnType = 'url')
    {
        $imageId = get_post_thumbnail_id($postId);

        return $this->getImage($imageId, $sizeName, $returnType);
    }

    /**
     * Wrapper for getImage(), using post meta
     * @param int    $postId
     * @param string $key
     * @param string $sizeName
     * @param string $returnType
     * @param string $delimiter
     * @return array|string
     * @see self::getImage()
     */
    public function getMetaImage($postId, $key, $sizeName = 'large', $returnType = 'url', $delimiter = '-')
    {
        $prefixedKey = $this->init->getPrefix() . $delimiter . $key;
        $imageId = get_post_meta($postId, $prefixedKey, true);

        return $this->getImage($imageId, $sizeName, $returnType);
    }

    /**
     * Get the slug of a taxonomy
     * @param  string $taxonomyName
     * @return string|null
     */
    public function getTaxonomySlug($taxonomyName)
    {
        $taxonomies = get_taxonomies(['name' => $taxonomyName], 'objects');

        if (isset($taxonomies[$taxonomyName]->rewrite['slug'])) {
            return $taxonomies[$taxonomyName]->rewrite['slug'];
        }

        return null;
    }

    /**
     * Get a list of items of a taxonomy
     * @param  string $taxonomy
     * @return array
     */
    public function getTaxonomyItems($taxonomy)
    {
        return get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => false
        ]);
    }

    /**
     * Get the taxonomies a post has
     * @param  int          $postId
     * @param  string|array $taxonomies
     * @return array
     */
    public function getPostTaxonomies($postId, $taxonomies)
    {
        return wp_get_object_terms($postId, $taxonomies);
    }

    /**
     * Get an option, implicitly using a prefix
     * @param  $name The name of the option (without prefix)
     * @return string|null
     */
    public function getOption($name)
    {
        $optionName = $this->init->getPrefix() . '_' . $name;
        $value = trim(get_option($optionName));

        if ($value === '') {
            return null;
        }

        return $value;
    }

    /**
     * Set an option, implicitly using a prefix
     * @param  $name The name of the option (without prefix)
     * @param  $value
     * @return self
     */
    public function setOption($name, $value)
    {
        $optionName = $this->init->getPrefix() . '_' . $name;
        update_option($optionName, $value);

        return $this;
    }

    /**
     * Delete an option, implicitly using a prefix
     * @param  string $name The name of the option (without prefix)
     * @return self
     */
    public function deleteOption($name, $prefixAppend = '_')
    {
        $optionName = $this->init->getPrefix() . '_' . $name;
        delete_option($optionName);

        return $this;
    }

    /**
     * Wrapper for get_post_meta(), implicitly using a prefix
     * @param  int    $postId
     * @param  string $key
     * @param  bool   $single
     * @return mixed
     */
    public function getPostMeta($postId, $key, $single = true)
    {
        $metaKey = $this->init->getPrefix() . '_' . $key;

        return get_post_meta($postId, $metaKey, $single);
    }
}
