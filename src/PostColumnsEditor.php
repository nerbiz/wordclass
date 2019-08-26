<?php

namespace Nerbiz\Wordclass;

class PostColumnsEditor implements WordclassInterface
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
    protected $postColumnsToAdd = [];

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
            add_action('pre_get_posts', function (\WP_Query $query) {
                if (! $this->onCurrentPostTypeScreen($query)) {
                    return;
                }

                // Call the custom sorting callback, if it exists
                $orderBy = $query->get('orderby');
                if (isset(static::$sortingCallbacks[$orderBy])) {
                    static::$sortingCallbacks[$orderBy]($query);
                }
            }, 10);

            static::$sortingHookAdded = true;
        }
    }

    /**
     * Indicates whether the current page is the admin page for this post type
     * @param \WP_Query $query
     * @return bool
     */
    protected function onCurrentPostTypeScreen(\WP_Query $query): bool
    {
        global $pagenow;
        $currentPostType = $query->get('post_type');

        return (is_admin() && $pagenow === 'edit.php' && $currentPostType === $this->postType);
    }

    /**
     * @param string   $name
     * @param callable $callback
     * @return void
     * @throws \Exception
     */
    public static function addOrderByMethod(string $name, callable $callback): void
    {
        if (! is_callable($callback)) {
            throw new \Exception(sprintf(
                "%s(): sorting callback '%s' is not callable",
                __METHOD__,
                is_object($callback) ? get_class($callback) : $callback
            ));
        }

        // Add the sorting callback
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
        $this->postColumnsToAdd[] = $postColumn;
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
        add_filter('manage_'.$this->postType.'_posts_columns', function (array $columns) {
            if (count($this->postColumnsToAdd) < 1) {
                return $columns;
            }

            // First add the columns that need to be placed at the end
            foreach ($this->postColumnsToAdd as $postColumn) {
                if ($postColumn->getAfter() === null) {
                    $columns[$postColumn->getName()] = $postColumn->getLabel();
                }
            }

            // Then add columns that need to be placed after another
            $adjustedColumns = [];
            foreach ($this->postColumnsToAdd as $postColumn) {
                if ($postColumn->getAfter() !== null) {
                    foreach ($columns as $key => $column) {
                        $adjustedColumns[$key] = $column;

                        // Insert the new column after the 'title' column
                        if ($key === $postColumn->getAfter()) {
                            $adjustedColumns[$postColumn->getName()] = $postColumn->getLabel();
                        }
                    }
                }
            }

            // Add any missing columns, in case the 'after' column was not found
            foreach ($this->postColumnsToAdd as $postColumn) {
                if (! isset($adjustedColumns[$postColumn->getName()])) {
                    $adjustedColumns[$postColumn->getName()] = $postColumn->getLabel();
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
        add_action('manage_' . $this->postType . '_posts_custom_column', function (string $column, int $postId) {
            foreach ($this->postColumnsToAdd as $postColumn) {
                if ($column === $postColumn->getName()) {
                    $renderFunction = $postColumn->getRenderFunction();

                    if (is_callable($renderFunction)) {
                        echo $renderFunction($postId);
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
            foreach ($this->postColumnsToAdd as $postColumn) {
                $columns[$postColumn->getName()] = $postColumn->getOrderBy();
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

        add_action('pre_get_posts', function (\WP_Query $query) {
            if (! $this->onCurrentPostTypeScreen($query)) {
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
