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
    protected $addColumns = [];

    /**
     * A list of columns to move
     * @var string[]
     */
    protected $moveColumns = [];

    /**
     * A list of columns to remove
     * @var string[]
     */
    protected $removeColumns = [];

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
        $this->addColumns[] = $postColumn;

        return $this;
    }

    /**
     * @param string $columnName
     * @param string $afterColumnName
     * @return self
     */
    public function moveColumn(string $columnName, string $afterColumnName): self
    {
        $this->moveColumns[] = [
            'name' => $columnName,
            'after' => $afterColumnName,
        ];

        return $this;
    }

    /**
     * @param string $columnName
     * @return self
     */
    public function removeColumn(string $columnName): self
    {
        $this->removeColumns[] = $columnName;

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
            // Move columns
            foreach ($this->moveColumns as $moveColumn) {
                if (isset($columns[$moveColumn['name']])) {
                    // Remove the column before placing it again
                    $label = $columns[$moveColumn['name']];
                    unset($columns[$moveColumn['name']]);

                    $columns = $this->spliceAssociativeArray($columns, $moveColumn['after'], [
                        $moveColumn['name'] => $label,
                    ]);
                }
            }

            // Get the IDs of all current and custom columns
            $allColumnIds = array_merge(
                array_keys($columns),
                array_map(function (PostColumn $postColumn) {
                    return $postColumn->getId();
                }, $this->addColumns)
            );

            // This loop is needed, because before the foreach is done,
            // not all the columns are added, so the 'after' value wouldn't work
            // with a column that doesn't exist yet in the array
            $addColumns = $this->addColumns;
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
                        $columns = $this->spliceAssociativeArray($columns, $after, [
                            $id => $label,
                        ]);
                    } else {
                        // Add the new column at the end of the array
                        $columns[$id] = $label;
                    }

                    // Take the new column out of the array
                    unset($addColumns[$key]);
                }
            }

            // Remove columns
            foreach ($this->removeColumns as $columnName) {
                if (isset($columns[$columnName])) {
                    unset($columns[$columnName]);
                }
            }

            return $columns;
        }, 10);
    }

    /**
     * Insert (splice) an entry into an associative array
     * @param array  $original
     * @param string $afterKey
     * @param array  $insert
     * @return array
     */
    protected function spliceAssociativeArray(
        array $original,
        string $afterKey,
        array $insert
    ): array {
        // Get the offset of where to inser the item
        $offset = array_search($afterKey, array_keys($original)) + 1;

        return array_merge(
            array_slice($original, 0, $offset, true),
            $insert,
            array_slice($original, $offset, null, true)
        );
    }

    /**
     * Set the render functions from the PostColumns
     * @return void
     */
    protected function applyRenderFunctions(): void
    {
        add_action('manage_' . $this->postType . '_posts_custom_column', function (string $columnId, int $postId) {
            foreach ($this->addColumns as $postColumn) {
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
            foreach ($this->addColumns as $postColumn) {
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
