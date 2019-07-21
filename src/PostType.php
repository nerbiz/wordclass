<?php

namespace Nerbiz\Wordclass;

class PostType implements WordclassInterface
{
    /**
     * @var Init
     */
    protected $init;

    /**
     * The ID of the post type
     * @var string
     */
    protected $id;

    /**
     * The slug of the post type
     * @var string
     */
    protected $slug;

    /**
     * The name of the post type
     * @var string
     */
    protected $name;

    /**
     * The singular name of the post type
     * @var string
     */
    protected $singularName;

    /**
     * The description of the post type
     * @var string
     */
    protected $description;

    /**
     * The labels for the post type
     * @var array
     */
    protected $labels = [];

    /**
     * The features the post type supports
     * @var array
     */
    protected $supports = ['title', 'editor'];

    /**
     * The arguments for the post type
     * @var array
     */
    protected $arguments = [];

    /**
     * The taxonomies the post type has/belongs to
     * @var array
     */
    protected $taxonomies = [];

    public function __construct()
    {
        $this->init = Factory::make('Init');
    }

    /**
     * Set the post type ID, will be prefixed
     * @param  string $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $this->init->getPrefix() . '_' . $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param  string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param  string $slug
     * @return self
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @param  string $singularName
     * @return self
     */
    public function setSingularName(string $singularName): self
    {
        $this->singularName = $singularName;

        return $this;
    }

    /**
     * @param  string $description
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param  array $labels
     * @return self
     */
    public function setLabels(array $labels): self
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * @param array $supports
     * @return self
     */
    public function setSupports(array $supports): self
    {
        $this->supports = $supports;

        return $this;
    }

    /**
     * @param  array $arguments
     * @return self
     */
    public function setArguments(array $arguments): self
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @param  string|array|Taxonomy $taxonomies
     * @return self
     */
    public function setTaxonomies($taxonomies): self
    {
        if (!is_array($taxonomies)) {
            $taxonomies = [$taxonomies];
        }

        // Make sure the post types are a string
        foreach ($taxonomies as $key => $name) {
            // Taxonomy objects can be passed
            if ($name instanceof Taxonomy) {
                $taxonomies[$key] = $name->getId();
            } else {
                $taxonomies[$key] = (string)$name;
            }
        }

        $this->taxonomies = $taxonomies;

        return $this;
    }

    /**
     * Get the default labels, replaced with custom ones
     * @return array
     */
    public function getLabels(): array
    {
        return array_replace([
            'name' => $this->name,
            'singular_name' => $this->singularName,
            'menu_name' => $this->name,
            'name_admin_bar' => $this->singularName,
            'archives' => sprintf(__('%s archive', 'wordclass'), $this->name),
            'parent_item_colon' => sprintf(__('Parent %s:', 'wordclass'), $this->singularName),
            'all_items' => sprintf(__('All %s', 'wordclass'), $this->name),
            'add_new_item' => sprintf(__('Add new %s', 'wordclass'), $this->singularName),
            'add_new' => sprintf(__('Add new %s', 'wordclass'), $this->singularName),
            'new_item' => sprintf(__('New %s', 'wordclass'), $this->singularName),
            'edit_item' => sprintf(__('Edit %s', 'wordclass'), $this->singularName),
            'update_item' => sprintf(__('Update %s', 'wordclass'), $this->singularName),
            'view_item' => sprintf(__('View %s', 'wordclass'), $this->singularName),
            'search_items' => sprintf(__('Search %s', 'wordclass'), $this->singularName),
            'not_found' => __('Not found', 'wordclass'),
            'not_found_in_trash' => __('Not found in trash', 'wordclass'),
            'featured_image' => __('Featured image', 'wordclass'),
            'set_featured_image' => __('Set featured image', 'wordclass'),
            'remove_featured_image' => __('Remove featured image', 'wordclass'),
            'use_featured_image' => __('Use as featured image', 'wordclass'),
            'insert_into_item' => sprintf(__('Insert into %s', 'wordclass'), $this->singularName),
            'uploaded_to_this_item' => sprintf(__('Uploaded to this %s', 'wordclass'), $this->singularName),
            'items_list' => sprintf(__('%s list', 'wordclass'), $this->name),
            'items_list_navigation' => sprintf(__('%s list navigation', 'wordclass'), $this->name),
            'filter_items_list' => sprintf(__('Filter %s list', 'wordclass'), $this->name),
        ], $this->labels);
    }

    /**
     * Get the default arguments, replaced with custom ones
     * @return array
     */
    public function getArguments(): array
    {
        return array_replace_recursive([
            'label' => $this->name,
            'description' => $this->description,
            'labels' => $this->getLabels(),
            'supports' => $this->supports,
            'taxonomies' => $this->taxonomies,
            'rewrite' => [
                'slug' => $this->slug,
            ],
        ], $this->arguments);
    }

    /**
     * Add the post type
     * @return self
     * @throws \ReflectionException
     */
    public function create(): self
    {
        // Derive a slug, if it's not set yet
        if ($this->slug === null) {
            $this->slug = Factory::make('Utilities')->createSlug($this->name);
        }

        add_action('init', function () {
            register_post_type($this->getId(), $this->getArguments());
        }, 10);

        return $this;
    }
}
