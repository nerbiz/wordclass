<?php

namespace Nerbiz\WordClass\InputFields;

use WP_Post;

class PostInputField extends SelectInputField
{
    /**
     * The post types to select from
     * @var array
     */
    protected $postTypes = [];

    /**
     * @param string      $name
     * @param string      $label
     * @param string|null $description
     * @param array       $postTypes
     */
    public function __construct(
        string $name,
        string $label,
        ?string $description = null,
        array $postTypes = []
    ) {
        $this->postTypes = $postTypes;
        $values = $this->createValuesArray();

        parent::__construct($name, $label, $description, $values);
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
            array_reduce($allPosts, function (array $current, WP_Post $post) {
                $postTypeObject = get_post_type_object($post->post_type);

                // Create the post type key if it doesn't exist yet
                if (! array_key_exists($postTypeObject->label, $current)) {
                    $current[$postTypeObject->label] = [];
                }

                // Add the post to the list
                $current[$postTypeObject->label][$post->ID] = $post->post_title;

                return $current;
            }, [])
        );
    }
}
