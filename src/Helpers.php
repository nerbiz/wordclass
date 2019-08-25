<?php

namespace Nerbiz\Wordclass;

class Helpers implements WordclassInterface
{
    /**
     * @var Init
     */
    protected $init;

    public function __construct()
    {
        $this->init = Factory::make('Init');
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
    public function getImage(int $imageId, string $sizeName = 'large', string $returnType = 'url')
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
     * @return string|array
     * @see Helpers::getImage()
     */
    public function getFeaturedImage(int $postId, string $sizeName = 'large', string $returnType = 'url')
    {
        $imageId = get_post_thumbnail_id($postId);

        return $this->getImage((int)$imageId, $sizeName, $returnType);
    }

    /**
     * Get the slug of a taxonomy
     * @param  string $taxonomyName
     * @return string|null
     */
    public function getTaxonomySlug(string $taxonomyName): ?string
    {
        $taxonomies = get_taxonomies(['name' => $taxonomyName], 'objects');

        if (isset($taxonomies[$taxonomyName]->rewrite['slug'])) {
            return $taxonomies[$taxonomyName]->rewrite['slug'];
        }

        return null;
    }

    /**
     * Get an option, implicitly using a prefix
     * @param  string $name The name of the option (without prefix)
     * @return string|null
     */
    public function getOption(string $name): ?string
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
     * @param  string $name The name of the option (without prefix)
     * @param  mixed  $value
     * @return self
     */
    public function setOption(string $name, $value): self
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
    public function deleteOption(string $name): self
    {
        $optionName = $this->init->getPrefix() . '_' . $name;
        delete_option($optionName);

        return $this;
    }
}
