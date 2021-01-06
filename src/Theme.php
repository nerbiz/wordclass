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
     * Allow the use of HTML5 in core WordPress features
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
     * Remove the <meta name="generator" content="WordPress [version]" /> tag
     * @return self
     */
    public function removeGeneratorMeta(): self
    {
        remove_action('wp_head', 'wp_generator');

        return $this;
    }

    /**
     * @deprecated 2.1.0
     * @see Pages::automaticWindowTitle()
     * @return self
     */
    public function automaticTitle(): self
    {
        (new Pages())->automaticWindowTitle();

        return $this;
    }

    /**
     * @deprecated 2.2.0
     * @see Media::setFeaturedImageSize()
     * @return self
     */
    public function setFeaturedImageSize(int $width, int $height, bool $crop = false): self
    {
        (new Media())->setFeaturedImageSize($width, $height, $crop);

        return $this;
    }

    /**
     * @deprecated 2.2.0
     * @see Media::addImageSize()
     * @return self
     */
    public function addImageSize(
        string $name,
        string $nameInChooser,
        int $width,
        int $height,
        bool $crop = false
    ): self {
        (new Media())->addImageSize($name, $nameInChooser, $width, $height, $crop);

        return $this;
    }

    /**
     * @deprecated 2.5.0
     * @see Assets::hashVersionParameters()
     * @return self
     */
    public function hashAssetVersions(string $salt): self
    {
        (new Assets())->hashVersionParameters($salt);

        return $this;
    }
}
