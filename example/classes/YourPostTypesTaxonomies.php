<?php

namespace YourNamespace;

use Nerbiz\WordClass\Helpers;
use Nerbiz\WordClass\PostType;
use Nerbiz\WordClass\Taxonomy;

class YourPostTypesTaxonomies
{
    /**
     * @return PostType
     */
    public function createCalendarItemPostType(): PostType
    {
        return (new PostType('calendar_item'))
            ->setSingularName('Calendar item')
            ->setPluralName('Calendar items')
            ->setDescription('Optional description for the post type')
            // Define what the post type supports
            ->setFeatures(['title', 'editor', 'thumbnail'])
            // If taxonomies are registered before this post type, use this
            ->setTaxonomies([
                Helpers::withPrefix('calendar_item_tag'),
            ])
            // All labels are implicitly set based on singular and plural names,
            // but can be overwritten individually
            ->setLabels([
                'items_list' => 'List of items',
                'update_item' => 'Update it',
            ])
            // Default arguments can be overwritten individually as well
            ->setArguments([
                'public' => false,
                'show_ui' => true,
            ])
            ->register();
    }

    /**
     * @return Taxonomy
     */
    public function createCalendarItemTagTaxonomy(): Taxonomy
    {
        return (new Taxonomy('calendar_item_tag'))
            ->setSingularName('Calendar item tag')
            ->setPluralName('Calendar item tags')
            ->setDescription('Optional description for the taxonomy')
            // If post types are registered before this taxonomy, use this
            ->setPostTypes([
                Helpers::withPrefix('calendar_item'),
            ])
            // All labels are implicitly set based on singular and plural names,
            // but can be overwritten individually
            ->setLabels([
                'menu_name' => 'Menu label',
                'not_found' => 'Doesn\'t exist',
            ])
            // Default arguments can be overwritten individually as well
            ->setArguments([
                'public' => false,
                'show_ui' => true,
            ])
            ->register();
    }
}
