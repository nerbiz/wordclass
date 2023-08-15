<?php

namespace YourNamespace;

use Nerbiz\WordClass\Helpers;
use Nerbiz\WordClass\PostColumn;
use Nerbiz\WordClass\PostColumnsEditor;
use WP_Query;

class YourPostColumns
{
    /**
     * Register post columns
     * @return void
     */
    public function register(): void
    {
        // Add a custom 'order by' method
        // It needs a name and a function that defines the query
        PostColumnsEditor::addOrderByMethod('meta_from_timestamp', function (WP_Query $query) {
            // In this example, ordering is enabled for a timestamp meta value
            $query->set('orderby', 'meta_value_num');
            $query->set('meta_key', '_calendar_item_from_timestamp');
        });

        // Create a new column
        $startDateColumn = (new PostColumn('start_date', 'Start date'))
            // Use the newly created 'order by' method
            ->setOrderBy('meta_from_timestamp')
            // Place the column after the 'title' column
            ->setAfter('title')
            // Decide what the column shows in each row
            ->setRenderFunction(function (int $postId) {
                $fromTimestamp = get_post_meta($postId, '_calendar_item_from_timestamp', true);

                return (trim($fromTimestamp) !== '')
                    ? date('j F Y, G:i', (int) $fromTimestamp)
                    : '-';
            });

        // Change the post columns for the calendar items post overview
        $postType = Helpers::withPrefix('calendar_item');
        // Multiple post type overviews can be changed (array)
        (new PostColumnsEditor([$postType]))
            // Remove a column from the overview
            ->removeColumn('author')
            // Move a column to the right of another column
            ->moveColumn('title', 'date')
            // Add the new column
            ->addColumn($startDateColumn)
            // Set the newly created 'order by' method as default (optional)
            ->setDefaultOrderByMethod('meta_from_timestamp')
            ->setDefaultOrder('desc')
            // Don't forget to apply the changes
            ->apply();
    }
}
