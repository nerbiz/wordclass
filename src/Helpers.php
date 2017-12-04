<?php

namespace Wordclass;

class Helpers {
    use Traits\CanSetPrefix;



    /**
     * Get the URL of a featured image
     * @param  Integer  $imageid  The ID of the image
     * @param  String   $size     The size of the image
     *                              thumbnail | medium | large | full | <custom>
     * @param  String   $type     The type of data to return
     *                              url: the image URL
     *                              array: [url, width, height, is_intermediate]
     *                              html: an <img> element
     * @return String|Array
     */
    public static function getImage($imageid, $size='full', $type='url') {
        switch($type) {
            case 'url':
                return wp_get_attachment_image_url($imageid, $size);
                break;

            case 'array':
                return wp_get_attachment_image_src($imageid, $size);
                break;

            case 'html':
                return wp_get_attachment_image($imageid, $size);
                break;
            
            default:
                return '';
                break;
        }
    }



    /**
     * Wrapper for getImage, using post featured image
     */
    public static function getFeaturedImage($postid, $size='full', $type='url') {
        $imageId = get_post_thumbnail_id($postid);
        return static::getImage($imageId, $size, $type);
    }



    /**
     * Wrapper for getImage, using post meta
     */
    public static function getMetaImage($postid, $metaname, $size='full', $type='url') {
        $imageId = get_post_meta($postid, $metaname, true);
        return static::getImage($imageId, $size, $type);
    }



    /**
     * Get the slug of a taxonomy
     * @param  String  $name  string: gets slug of given taxonomy
     * @return String
     */
    public static function getTaxonomySlug($name) {
        $taxonomy = get_taxonomies(['name' => $name], 'objects');
        return $taxonomy[$name]->rewrite['slug'];
    }



    /**
     * Get a list of items, of a given taxonomy
     * @param  String  $taxonomy
     * @return Array
     */
    public static function getTaxonomyItems($taxonomy) {
        return get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => false
        ]);
    }



    /**
     * Get the taxonomies a post has
     * @param  Integer  $postid
     * @param  String   $taxonomy
     * @return Array
     */
    public static function getPostTaxonomies($postid, $taxonomy) {
        return wp_get_object_terms($postid, $taxonomy);
    }



    /**
     * Get or set an option from/in the Wordpress options table
     * @param   String  $name          The name of the option (without prefix)
     * @param   Mixed   $value         If null, this returns the value of the option
     *                                   returns false if the option doesn't exist, or is empty
     *                                 If not null, this will add or update the option
     * @param   String  $prefixAppend  The character(s) between prefix and name
     * @return  Mixed  (Boolean when a value is given, true on success, false on failure, or unchanged)
     */
    public static function option($name, $value=null, $prefixAppend='_') {
        if( ! is_string($name))
            return false;

        if(trim($name) == '')
            return false;

        // Get the option
        if($value === null)
            return get_option(static::prefix() . $prefixAppend . $name);

        // Add or update the option
        else
            return update_option(static::prefix() . $prefixAppend . $name, $value);
    }



    /**
     * Delete an option from the Wordpress options table
     * @param  String   $name          The name of the option (without prefix)
     * @param  String   $prefixAppend  The character(s) between prefix and name
     * @return Boolean  true on success, false on failure
     */
    public static function deleteOption($name, $prefixAppend='_') {
        if( ! is_string($name))
            return false;

        if(trim($name) == '')
            return false;

        return delete_option(static::prefix() . $prefixAppend . $name);
    }
}
