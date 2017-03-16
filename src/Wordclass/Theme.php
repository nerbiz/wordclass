<?php

namespace Wordclass;

class Theme {
    use Traits\CanSetTextDomain;



    /**
     * Enable the 'featured image' metabox on page/post edit screens
     * @param Null|String|Array  $posttypes  (Optional) Enable for specific post types only
     */
    public static function enableFeaturedImages($posttypes=null) {
        if($posttypes != null  &&  ! is_array($posttypes))
            $posttypes = [$posttypes];

        add_action('after_setup_theme', function() use($posttypes) {
            if($posttypes == null)
                add_theme_support('post-thumbnails');
            else
                add_theme_support('post-thumbnails', $posttypes);
        });
    }



    /**
     * Set the size of the featured images
     * @param  Integer  $width
     * @param  Integer  $height
     * @param  Boolean  $crop        Whether to resize (false) or crop (true) images
     */
    public static function featuredImageSize($width, $height, $crop=false) {
        add_action('after_setup_theme', function() use($width, $height, $crop) {
            set_post_thumbnail_size($width, $height, $crop);
        });
    }



    /**
     * Add a new image size, and add it to the size chooser
     * @param String   $name        Key name for the $sizes array
     * @param String   $optionName  Name in the size chooser
     * @param Integer  $width
     * @param Integer  $height
     * @param Boolean  $crop        Whether to resize (false) or crop (true) images
     */
    public function addImageSize($name, $optionName, $width, $height, $crop=false) {
        add_action('after_setup_theme', function() use($name, $optionName, $width, $height, $crop) {
            add_image_size($name, $width, $height, $crop);

            add_filter('image_size_names_choose', function($sizes) use($name, $optionName) {
                $sizes[$name] = __($optionName, static::$_textDomainStatic);
                return $sizes;
            });
        });
    }



    /**
     * Register custom menu positions
     * @param  Array  $menus  Menus in location:description pairs
     */
    public static function addMenus($menus) {
        add_action('after_setup_theme', function() use($menus) {
            foreach($menus as $location => $description)
                register_nav_menu($location, __($description, static::$_textDomainStatic));
        });
    }
}
