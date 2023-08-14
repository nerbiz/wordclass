<?php

namespace Nerbiz\WordClass;

class Theme
{
    /**
     * Enable the featured image on post edit screens
     * @param array $postTypes Enable for all post types (empty array)
     *                         or specific types (array of strings and/or PostType objects)
     * @return self
     */
    public function enableFeaturedImages(array $postTypes = []): self
    {
        add_action('after_setup_theme', function () use ($postTypes) {
            // Enable for all post types
            if (count($postTypes) === 0) {
                add_theme_support('post-thumbnails');
            } else {
                // Enable only for the give post types
                $postTypes = array_map(
                    fn ($postType) => ($postType instanceof PostType)
                        ? $postType->getName()
                        : $postType,
                    $postTypes
                );

                add_theme_support('post-thumbnails', $postTypes);
            }
        });

        return $this;
    }

    /**
     * Allow the use of HTML5 in core WordPress features
     * @param array|null $features The list of enabled HTML5 features (all features if null)
     * @return self
     */
    public function enableHtml5Support(?array $features = null): self {
        $features ??= ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script'];

        add_action('after_setup_theme', fn () => add_theme_support('html5', $features));

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
}
