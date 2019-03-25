<?php

namespace Nerbiz\Wordclass\SettingInputs;

class Post extends AbstractSettingInput
{
    /**
     * {@inheritdoc}
     */
    public function render(): string
    {
        // Get all the posts
        $allPosts = get_posts([
            'post_type' => $this->getPostTypes(),
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'post_type',
            'order' => 'asc',
        ]);

        $dropdown = sprintf('<select name="%s">', $this->arguments['name']);

        // Add a placeholder option
        $dropdown .= sprintf(
            '<option value="">%s</option>',
            __('- Choose one -', 'wordclass')
        );

        foreach ($this->getDropdownOptionsArray($allPosts) as $psotType => $properties) {
            // Create an optgroup per post type
            $dropdown .= sprintf('<optgroup label="%s">', $properties['label']);

            // Add the posts in the optgroup
            foreach ($properties['posts'] as $postId => $postTitle) {
                $dropdown .= sprintf(
                    '<option value="%s" %s>%s</option>',
                    $postId,
                    selected($postId, get_option($this->arguments['name']), false),
                    $postTitle
                );
            }

            $dropdown .= '</optgroup>';
        }

        $dropdown .= '</select>';

        return $dropdown;
    }

    /**
     * See which post types to use for the posts query
     * @return array
     */
    protected function getPostTypes(): array
    {
        // Use (almost) all post types if no specific type is given
        if (! isset($this->arguments['post_type'])) {
            return array_merge(
                ['post', 'page'],
                array_values(get_post_types(['_builtin' => false]))
            );
        } else {
            if (! is_array($this->arguments['post_type'])) {
                return [$this->arguments['post_type']];
            }

            return $this->arguments['post_type'];
        }
    }

    /**
     * Create an array in preparation for constructing the dropdown options
     * @param array $posts
     * @return array
     */
    protected function getDropdownOptionsArray(array $posts): array
    {
        $dropdownOptions = [];

        foreach ($posts as $post) {
            // Create the post type key if it doesn't exist yet
            if (! array_key_exists($post->post_type, $dropdownOptions)) {
                $postTypeObject = get_post_type_object($post->post_type);
                $dropdownOptions[$post->post_type] = [
                    'label' => $postTypeObject->label,
                    'posts' => [],
                ];
            }

            // Add the post to the list, as a [postId => postTitle] pair
            $dropdownOptions[$post->post_type]['posts'][$post->ID] = $post->post_title;
        }

        return $dropdownOptions;
    }
}
