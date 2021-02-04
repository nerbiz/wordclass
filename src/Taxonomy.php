<?php

namespace Nerbiz\WordClass;

class Taxonomy
{
    /**
     * The ID of the taxonomy
     * @var string
     */
    protected $id;

    /**
     * The slug of the taxonomy
     * @var string
     */
    protected $slug;

    /**
     * The singular name of the taxonomy
     * @var string
     */
    protected $singularName;

    /**
     * The plural name of the taxonomy
     * @var string
     */
    protected $pluralName;

    /**
     * The description of the taxonomy
     * @var string
     */
    protected $description;

    /**
     * The labels of the taxonomy
     * @var array
     */
    protected $labels = [];

    /**
     * The post types that have this taxonomy
     * @var array
     */
    protected $postTypes = [];

    /**
     * The arguments of the taxonomy
     * @var array
     */
    protected $arguments = [];

    /**
     * @param string $id The ID of the taxonomy
     */
    public function __construct(string $id)
    {
        $this->id = Init::getPrefix() . '_' . $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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
     * @return string
     */
    public function getSlug(): string
    {
        if (! isset($this->slug)) {
            $this->slug = Utilities::createSlug($this->pluralName);
        }

        return $this->slug;
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
     * @param  string $pluralName
     * @return self
     */
    public function setPluralName(string $pluralName): self
    {
        $this->pluralName = $pluralName;

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
     * Get the default labels, replaced with custom ones
     * @return array
     */
    public function getLabels(): array
    {
        return array_replace_recursive([
            'name'                       => $this->pluralName,
            'singular_name'              => $this->singularName,
            'menu_name'                  => $this->pluralName,
            'all_items'                  => sprintf(__('All %s', 'wordclass'), $this->pluralName),
            'parent_item'                => sprintf(__('Parent %s', 'wordclass'), $this->singularName),
            'parent_item_colon'          => sprintf(__('Parent %s:', 'wordclass'), $this->singularName),
            'new_item_name'              => sprintf(__('New %s name', 'wordclass'), $this->singularName),
            'add_new_item'               => sprintf(__('Add new %s', 'wordclass'), $this->singularName),
            'edit_item'                  => sprintf(__('Edit %s', 'wordclass'), $this->singularName),
            'update_item'                => sprintf(__('Update %s', 'wordclass'), $this->singularName),
            'view_item'                  => sprintf(__('View %s', 'wordclass'), $this->singularName),
            'separate_items_with_commas' => sprintf(__('Separate %s with commas', 'wordclass'), $this->pluralName),
            'add_or_remove_items'        => sprintf(__('Add or remove %s', 'wordclass'), $this->pluralName),
            'choose_from_most_used'      => __('Choose from the most used', 'wordclass'),
            'popular_items'              => sprintf(__('Popular %s', 'wordclass'), $this->pluralName),
            'search_items'               => sprintf(__('Search %s', 'wordclass'), $this->pluralName),
            'not_found'                  => __('Not found', 'wordclass'),
            'no_terms'                   => sprintf(__('No %s', 'wordclass'), $this->pluralName),
            'items_list'                 => sprintf(__('%s list', 'wordclass'), $this->pluralName),
            'items_list_navigation'      => sprintf(__('%s list navigation', 'wordclass'), $this->pluralName),
        ], $this->labels);
    }

    /**
     * An array of strings and/or PostType objects
     * @param  array $postTypes
     * @return self
     */
    public function setPostTypes(array $postTypes): self
    {
        // Make sure the post types are a string
        foreach ($postTypes as $key => $type) {
            // Post type objects can be passed
            if ($type instanceof PostType) {
                $postTypes[$key] = $type->getId();
            } else {
                $postTypes[$key] = (string) $type;
            }
        }

        $this->postTypes = $postTypes;

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
     * Get the default arguments, merged with custom ones
     * @return array
     */
    public function getArguments(): array
    {
        return array_replace_recursive([
            'label'       => $this->pluralName,
            'labels'      => $this->getLabels(),
            'description' => $this->description,
            'rewrite'     => [
                'slug' => $this->getSlug(),
            ],
        ], $this->arguments);
    }

    /**
     * Register the taxonomy
     * @return self
     */
    public function register(): self
    {
        add_action('init', function () {
            register_taxonomy($this->id, $this->postTypes, $this->getArguments());

            foreach ($this->postTypes as $postType) {
                register_taxonomy_for_object_type($this->id, $postType);
            }
        }, 10);

        return $this;
    }
}
