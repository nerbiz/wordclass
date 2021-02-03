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
            if (count($this->columnsToAdd) < 1) {
                return $columns;
            }

            // Get the IDs of all current and custom columns
            $allColumnIds = array_merge(
                array_keys($columns),
                array_map(function (PostColumn $postColumn) {
                    return $postColumn->getId();
                }, $this->columnsToAdd),
            );

            // This loop is needed, because before the foreach is done,
            // not all the columns are added, so the 'after' value wouldn't work
            // with a column that doesn't exist yet in the array
            $addColumns = $this->columnsToAdd;
            while (count($addColumns) > 0) {
                foreach ($addColumns as $key => $postColumn) {
                    $id = $postColumn->getId();
                    $label = $postColumn->getLabel();
                    $after = $postColumn->getAfter();
                    // Set the 'after' value to null, if that column doesn't exist
                    $after = in_array($after, $allColumnIds, true) ? $after : null;

                    if ($after !== null) {
                        // Skip for the next while-iteration, if the after-column doesn't exist
                        if (! isset($columns[$after])) {
                            continue;
                        }

                        // Insert the new column after another column
                        $offset = array_search($after, array_keys($columns)) + 1;
                        $columns = array_merge(
                            array_slice($columns, 0, $offset, true),
                            [$id => $label],
                            array_slice($columns, $offset, null, true)
                        );
                    } else {
                        // Add the new column at the end of the array
                        $columns[$id] = $label;
                    }

                    // Take the new column out of the array
                    unset($addColumns[$key]);
                }
            }

            // Remove columns
            foreach ($this->columnsToRemove as $columnName) {
                if (isset($columns[$columnName])) {
                    unset($columns[$columnName]);
                }
            }

            return $columns;
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
        if (! isset($this->defaultOrderByMethod, $this->defaultOrder)) {
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
