<?php

namespace Nerbiz\Wordclass;

class Theme
{
    /**
     * Enable the featured image on post edit screens
     * @param array|null $postTypes Array of strings and/or PostType objects,
     *                              enable for specific post types only
     * @return self
     */
    public function enableFeaturedImages(array $postTypes = null): self
    {
        add_action('after_setup_theme', function () use ($postTypes) {
            // Enable for all post types
            if ($postTypes === null) {
                add_theme_support('post-thumbnails');
            } else {
                // Enable only for the give post types
                foreach ($postTypes as $key => $postType) {
                    if ($postType instanceof PostType) {
                        $postTypes[$key] = $postType->getId();
                    }
                }

                add_theme_support('post-thumbnails', $postTypes);
            }
        });

        return $this;
    }

    /**
     * Set the size of featured images
     * @param  int  $width
     * @param  int  $height
     * @param  bool $crop Whether to resize (false) or crop (true) images
     * @return self
     */
    public function setFeaturedImageSize(int $width, int $height, bool $crop = false): self
    {
        add_action('after_setup_theme', function () use ($width, $height, $crop) {
            set_post_thumbnail_size($width, $height, $crop);
        });

        return $this;
    }

    /**
     * Allow the use of HTML5 in core Wordpress features
     * @param  array $features The list of features to enable HTML5 for
     * @return self
     */
    public function enableHtml5Support(
        array $features = ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']
    ): self {
        add_action('after_setup_theme', function () use ($features) {
            add_theme_support('html5', $features);
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
    public function addImageSize(
        string $name,
        string $nameInChooser,
        int $width,
        int $height,
        bool $crop = false
    ): self {
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
    public function addMenus(array $menus): self
    {
        add_action('after_setup_theme', function () use ($menus) {
            register_nav_menus($menus);
        });

        return $this;
    }

    /**
     * @deprecated since 2.1.0
     * @see Pages::automaticWindowTitle()
     * @return self
     */
    public function automaticTitle(): self
    {
        (new Pages())->automaticWindowTitle();

        return $this;
    }
}
