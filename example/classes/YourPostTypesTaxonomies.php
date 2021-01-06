<?php

namespace NewProject;

use Nerbiz\Wordclass\PostType;
use Nerbiz\Wordclass\Taxonomy;

class YourPostTypesTaxonomies
{
    /**
     * @return PostType
     */
    public function createCalendarItemPostType(): PostType
    {
        return (new PostType('calendar_item'))
            ->setSingularName(__('Calendar item', 'project-text-domain'))
            ->setPluralName(__('Calendar items', 'project-text-domain'))
            ->setSupports(['title', 'editor', 'thumbnail'])
            ->setArguments([
                'public' => true,
                'publicly_queryable' => true,
                // Enable on Gutenberg edit page
                'show_in_rest' => true,
            ])
            ->register();
    }

    /**
     * @param PostType $postType
     * @return Taxonomy
     */
    public function createCalendarItemTagTaxonomy(PostType $postType): Taxonomy
    {
        return (new Taxonomy('calendar_item_tag'))
            ->setSingularName(__('Calendar item tag', 'project-text-domain'))
            ->setPluralName(__('Calendar item tags', 'project-text-domain'))
            ->setPostTypes([$postType])
            ->setArguments([
                // Enable on Gutenberg edit page
                'show_in_rest' => true,
            ])
            ->register();
    }
}
