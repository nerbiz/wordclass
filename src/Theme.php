<?php

namespace Nerbiz\Wordclass;

class Theme
{
    /**
     * Enable the 'featured image' metabox on post edit screens
     * @param  null|string|array $postTypes (Optional) Enable for specific post types only
     * @return self
     */
    public function enableFeaturedImages($postTypes = null)
    {
        if ($postTypes !== null) {
            $postTypes = (array) $postTypes;
        }

        add_action('after_setup_theme', function () use ($postTypes) {
            // Enable for all post types
            if ($postTypes === null) {
                add_theme_support('post-thumbnails');
            }

            // Enable only for the give post types
            else {
                foreach ($postTypes as $postType) {
                    add_post_type_support($postType, 'post-thumbnails');
                }
            }
        });

        return $this;
    }

    /**
     * Allow the use of HTML5 in core Wordpress features
     * @param  array  $features  The list of features to enable HTML5 for
     * @return self
     */
    public function enableHtml5Support($features = null)
    {
        // By default, all features are HTML5-enabled
        if ($features === null) {
            $features = ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form'];
        }

        // Make sure the features are an array
        if (! is_array($features)) {
            $features = (array) $features;
        }

        add_action('after_setup_theme', function () use ($features) {
            add_theme_support('html5', $features);
        });

        return $this;
    }

    /**
     * Set the size of featured images
     * @param  int  $width
     * @param  int  $height
     * @param  bool $crop   Whether to resize (false) or crop (true) images
     * @return self
     */
    public function setFeaturedImageSize($width, $height, $crop = false)
    {
        add_action('after_setup_theme', function () use ($width, $height, $crop) {
            set_post_thumbnail_size($width, $height, $crop);
        });

        return $this;
    }

    /**
     * Add a new image size, and add it to the size chooser
     * @param  string $name          Key name for the $sizes array
     * @param  string $nameInChooser Name in the size chooser
     * @param  int    $width
     * @param  int    $height
     * @param  bool   $crop          Whether to resize (false) or crop (true) images
     * @return self
     */
    public function addImageSize($name, $nameInChooser, $width, $height, $crop = false)
    {
        add_action('after_setup_theme', function () use ($name, $nameInChooser, $width, $height, $crop) {
            add_image_size($name, $width, $height, $crop);

            // Set the image size name for the chooser
            add_filter('image_size_names_choose', function ($sizes) use ($name, $nameInChooser) {
                $sizes[$name] = $nameInChooser;
                return $sizes;
            });
        });

        return $this;
    }

    /**
     * Register menu positions
     * @param  array $menus Menus in location:description pairs
     * @return self
     */
    public function addMenus(array $menus)
    {
        add_action('after_setup_theme', function () use ($menus) {
            register_nav_menus($menus);
        });

        return $this;
    }

    /**
     * Let Wordpress handle the window title
     * When using this, remove the <title> tag from <head>
     * @return self
     */
    public function automaticTitle()
    {
        add_action('after_setup_theme', function () {
            add_theme_support('title-tag');
        });

        return $this;
    }
}
