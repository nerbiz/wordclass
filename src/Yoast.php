<?php

namespace Nerbiz\WordClass;

class Yoast
{
    /**
     * Add a custom breadcrumb
     * @param string|PostType $postType The post type to add a breadcrumb to
     * @param int             $postId   The ID of the post in the breadcrumb link
     * @param int             $offset   The location of the breadcrumb
     * @return self
     */
    public function addBreadcrumb($postType, int $postId, int $offset = -1): self
    {
        add_filter('wpseo_breadcrumb_links', function ($links) use ($postType, $postId, $offset) {
            if ($postType instanceof PostType) {
                $postType = $postType->getId();
            }

            // Adjust the breadcrumbs for the posts
            if (is_singular($postType)) {
                // Add the custom breadcrumb
                array_splice($links, $offset, 0, [
                    [
                        'url'  => get_permalink($postId),
                        'text' => get_the_title($postId),
                    ]
                ]);
            }

            return $links;
        });

        return $this;
    }
}
