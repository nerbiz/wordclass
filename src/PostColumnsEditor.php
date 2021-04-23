<?php

namespace Nerbiz\Wordclass;

use WP_Query;

class PostColumnsEditor
{
    /**
     * Sorting callbacks in [name => callback] pairs
     * @var callable[]
     */
    protected static $sortingCallbacks = [];

    /**
     * Indicates whether the 'pre_get_posts' hook has been added
     * @var bool
     */
    protected static $sortingHookAdded = false;

    /**
     * The post type to edit the columns of
     * @var string
     */
    protected $postType;

    /**
     * The default 'orderby' method to use
     * @var string|null
     */
    protected $defaultOrderByMethod = null;

    /**
     * The default 'order' value to use (asc / desc)
     * @var string|null
     */
    protected $defaultOrder = null;

    /**
     * A list of columns to add
     * @var PostColumn[]
     */
    protected $columnsToAdd = [];

    /**
     * A list of columns to remove
     * @var string[]
     */
    protected $columnsToRemove = [];

    /**
     * @param string $postType
     */
    public function __construct(string $postType)
    {
        $this->postType = $postType;

        if (! static::$sortingHookAdded) {
            static::$sortingHookAdded = true;

            add_action('pre_get_posts', function (WP_Query $query) {
                if (! $this->isOnAdminPage($query)) {
                    return;
                }

                // Call the custom sorting callback, if it exists
                $orderBy = $query->get('orderby');
                if (isset(static::$sortingCallbacks[$orderBy])) {
                    call_user_func(static::$sortingCallbacks[$orderBy], $query);
                }
            }, 10);
        }
    }

    /**
     * Indicates whether the current page is the admin overview page for this post type
     * @param WP_Query $query
     * @return bool
     */
    protected function isOnAdminPage(WP_Query $query): bool
    {
        global $pagenow;
        $currentPostType = $query->get('post_type');

        return (is_admin() && $pagenow === 'edit.php' && $currentPostType === $this->postType);
    }

    /**
     * @param string   $name
     * @param callable $callback
     * @return void
     */
    public static function addOrderByMethod(string $name, callable $callback): void
    {
        static::$sortingCallbacks[$name] = $callback;
    }

    /**
     * @param string $defaultOrderByMethod
     * @return self
     */
    public function setDefaultOrderByMethod(string $defaultOrderByMethod): self
    {
        $this->defaultOrderByMethod = $defaultOrderByMethod;

        return $this;
    }

    /**
     * @param string $defaultOrder
     * @return self
     */
    public function setDefaultOrder(string $defaultOrder): self
    {
        $this->defaultOrder = $defaultOrder;

        return $this;
    }

    /**
     * @param PostColumn $postColumn
     * @return self
     */
    public function addColumn(PostColumn $postColumn): self
    {
        $this->columnsToAdd[] = $postColumn;

        return $this;
    }

    /**
     * @param string $columnName
     * @return self
     */
    public function removeColumn(string $columnName): self
    {
        $this->columnsToRemove[] = $columnName;

        return $this;
    }

    /**
     * Apply the post column adjustments
     * @return void
     */
    public function apply()
    {
        $this->applyMutations();
        $this->applyRenderFunctions();
        $this->enableSorting();
        $this->applyDefaultSorting();
    }

    /**
     * Add and/or remove post columns
     * @return void
     */
    protected function applyMutations(): void
    {
        add_filter('manage_' . $this->postType . '_posts_columns', function (array $columns) {
            // First add the columns that need to be placed at the end
            foreach ($this->columnsToAdd as $postColumn) {
                if ($postColumn->getAfter() === null) {
                    $columns[$postColumn->getId()] = $postColumn->getLabel();
                }
            }

            // Then add columns that need to be placed after another
            $adjustedColumns = [];
            foreach ($this->columnsToAdd as $postColumn) {
                foreach ($columns as $key => $column) {
                    // Duplicate the column into the new array
                    $adjustedColumns[$key] = $column;

                    $after = $postColumn->getAfter();
                    if ($after === null) {
                        continue;
                    }

                    // Insert the new column after another column
                    if ($key === $after) {
                        $adjustedColumns[$postColumn->getId()] = $postColumn->getLabel();
                    }
                }
            }

            // Add any missing columns, in case the 'after' column was not found
            foreach ($this->columnsToAdd as $postColumn) {
                if (! isset($adjustedColumns[$postColumn->getId()])) {
                    $adjustedColumns[$postColumn->getId()] = $postColumn->getLabel();
                }
            }

            // Remove columns
            foreach ($this->columnsToRemove as $columnName) {
                if (isset($adjustedColumns[$columnName])) {
                    unset($adjustedColumns[$columnName]);
                }
            }

            return $adjustedColumns;
        }, 10);
    }

    /**
     * Set the render functions from the PostColumns
     * @return void
     */
    protected function applyRenderFunctions(): void
    {
        add_action('manage_' . $this->postType . '_posts_custom_column', function (string $columnId, int $postId) {
            foreach ($this->columnsToAdd as $postColumn) {
                if ($columnId === $postColumn->getId()) {
                    $renderFunction = $postColumn->getRenderFunction();

                    if (is_callable($renderFunction)) {
                        echo call_user_func($renderFunction, $postId);
                    }
                }
            }
        }, 10, 2);
    }

    /**
     * Enable sorting for the PostColumns
     * @return void
     */
    protected function enableSorting(): void
    {
        // Add the sorting names
        add_filter('manage_edit-' . $this->postType . '_sortable_columns', function (array $columns) {
            foreach ($this->columnsToAdd as $postColumn) {
                $orderBy = $postColumn->getOrderBy();
                if ($orderBy !== null) {
                    $columns[$postColumn->getId()] = $orderBy;
                }
            }

            return $columns;
        }, 10);
    }

    /**
     * Apply default sorting values
     * @return void
     */
    protected function applyDefaultSorting(): void
    {
        // At least 1 default value is needed
        if ($this->defaultOrderByMethod === null && $this->defaultOrder === null) {
            return;
        }

        add_action('pre_get_posts', function (WP_Query $query) {
            if (! $this->isOnAdminPage($query)) {
                return;
            }

            // Apply the default 'orderby' method, if it's set
            if (trim($query->get('orderby')) === '' && $this->defaultOrderByMethod !== null) {
                $query->set('orderby', $this->defaultOrderByMethod);
            }

            // Apply the default 'order' value, if it's set
            if (trim($query->get('order')) === '' && $this->defaultOrder !== null) {
                $query->set('order', $this->defaultOrder);
            }
        }, 9);
    }
}
