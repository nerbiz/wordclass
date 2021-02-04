<?php

namespace Nerbiz\WordClass;

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
}
