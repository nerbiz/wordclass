<?php

namespace Nerbiz\WordClass\InputFields;

use WP_Post;

class PostInputField extends SelectInputField
{
    /**
     * The post types to select from
     * @var array
     */
    protected array $postTypes = [];

    /**
     * @param string $name
     * @param string $label
     * @param array  $postTypes
     */
    public function __construct(string $name, string $label, array $postTypes = []) {
        $this->postTypes = $postTypes;
        $values = $this->createValuesArray();

        parent::__construct($name, $label, $values);
    }

    /**
     * Create an array for the select options
     * @return array
     */
    public function createValuesArray(): array
    {
        // Get all the posts
        $allPosts = get_posts([
            'post_type' => (count($this->postTypes) > 0)
                ? $this->postTypes
                : array_merge(
                    ['post', 'page'],
                    array_values(get_post_types(['_builtin' => false]))
                ),
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'post_type',
            'order' => 'asc',
        ]);

        return array_merge(
            ['' => __('- Choose one -', 'wordclass')],
            array_reduce($allPosts, function (array $postValues, WP_Post $post) {
                // Create the post type key if it doesn't exist yet
                if (! array_key_exists($post->post_type, $postValues)) {
                    $postValues[$post->post_type] = [];
                }

                // Add the post to the list
                $postValues[$post->post_type][$post->ID] = $post->post_title;

                return $postValues;
            }, [])
        );
    }
}
