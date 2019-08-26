<?php

namespace Nerbiz\Wordclass;

class Yoast implements WordclassInterface
{
    /**
     * Add a custom breadcrumb
     * @param string $postType The post type to add a breadcrumb to
     * @param int    $pageId The ID of the page in the breadcrumb link
     * @param int    $offset The location of the breadcrumb
     * @return void
     */
    public function addBreadcrumb(string $postType, int $pageId, int $offset = -1): void
    {
        add_filter('wpseo_breadcrumb_links', function ($links) use ($postType, $pageId, $offset) {
            // Adjust the breadcrumbs for the posts
            if (is_singular($postType)) {
                // Add the custom breadcrumb
                array_splice($links, $offset, 0, [
                    [
                        'url'  => get_permalink($pageId),
                        'text' => get_the_title($pageId),
                    ]
                ]);
            }

            return $links;
        });
    }
}
