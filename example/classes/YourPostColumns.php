<?php

namespace NewProject;

use Nerbiz\WordClass\Init;
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
        // Add a custom 'order by' method, based on the 'from' timestamp
        PostColumnsEditor::addOrderByMethod('meta_from_timestamp', function (WP_Query $query) {
            $query->set('orderby', 'meta_value_num');
            $query->set('meta_key', '_calendar_item_from_timestamp');
        });

        // Create a new column
        $startDateColumn = (new PostColumn('start_date', __('Start date', 'project-text-domain')))
            // Use the newly created 'order by' method
            ->setOrderBy('meta_from_timestamp')
            // Place the column after the 'title' column
            ->setAfter('title')
            // Decide what the column shows in each row
            ->setRenderFunction(function (int $postId) {
                $fromTimestamp = get_post_meta($postId, '_calendar_item_from_timestamp', true);
                if (trim($fromTimestamp) !== '') {
                    return date('j F Y, G:i');
                }

                return '-';
            });

        // Change the post columns for the calendar items post overview
        $postType = Init::getPrefix() . '_calendar_item';
        (new PostColumnsEditor([$postType]))
            // Remove a column from the overview
            ->removeColumn('author')
            // Add the new column
            ->addColumn($startDateColumn)
            // Set the newly created 'order by' method as default
            // Setting defaults is optional
            ->setDefaultOrderByMethod('meta_from_timestamp')
            ->setDefaultOrder('desc')
            // Don't forget to apply the changes
            ->apply();
    }
}
