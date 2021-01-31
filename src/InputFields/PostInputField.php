<?php

namespace Nerbiz\Wordclass\InputFields;

use WP_Post;

class PostInputField extends AbstractInputField
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

        parent::__construct($name, $label, $description);
    }

    /**
     * {@inheritdoc}
     */
    public function renderField(): string
    {
        // Get all the posts
        $allPosts = get_posts([
            'post_type' => $this->getPostTypes(),
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'post_type',
            'order' => 'asc',
        ]);

        $groupsString = array_reduce(
            $this->getDropdownPostGroups($allPosts),
            function ($current, $groupProperties) {
                // Create option elements
                $optionsString = array_reduce($groupProperties['posts'], function ($current, $post) {
                    return $current . sprintf(
                        '<option value="%s" %s>%s</option>',
                        $post->ID,
                        selected($post->ID, get_option($this->getPrefixedName()), false),
                        $post->post_title
                    );
                }, '');

                // Create an optgroup with options
                return $current . sprintf(
                    '<optgroup label="%s">
                        %s
                    </optgroup>',
                    $groupProperties['label'],
                    $optionsString
                );
            },
            ''
        );

        return sprintf(
            '<select name="%s">
                <option value="">%s</option>
                %s
            </select>',
            $this->getPrefixedName(),
            __('- Choose one -', 'wordclass'),
            $groupsString
        );
    }

    /**
     * See which post types to use for the posts query
     * @return array
     */
    protected function getPostTypes(): array
    {
        if (count($this->postTypes) > 0) {
            return $this->postTypes;
        }

        // If no type(s) are given, use all post types (except built-in)
        return array_merge(
            ['post', 'page'],
            array_values(get_post_types(['_builtin' => false]))
        );
    }

    /**
     * Create an array in preparation for constructing the dropdown options
     * @param WP_Post[] $posts
     * @return array
     */
    protected function getDropdownPostGroups(array $posts): array
    {
        return array_reduce($posts, function ($current, $post) {
            // Create the post type key if it doesn't exist yet
            if (! array_key_exists($post->post_type, $current)) {
                $postTypeObject = get_post_type_object($post->post_type);

                $current[$post->post_type] = [
                    'label' => $postTypeObject->label,
                    'posts' => [],
                ];
            }

            // Add the post to the list
            $current[$post->post_type]['posts'][] = $post;

            return $current;
        }, []);
    }
}
