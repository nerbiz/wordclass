<?php

namespace Nerbiz\WordClass;

class PostType
{
    /**
     * The name of the post type
     * @var string
     */
    protected string $name;

    /**
     * The slug of the post type
     * @var string
     */
    protected string $slug;

    /**
     * The singular name of the post type
     * @var string
     */
    protected string $singularName;

    /**
     * The plural name of the post type
     * @var string
     */
    protected string $pluralName;

    /**
     * The description of the post type
     * @var string|null
     */
    protected ?string $description = null;

    /**
     * The labels for the post type
     * @var array
     */
    protected array $labels = [];

    /**
     * The features the post type supports
     * @var array
     */
    protected array $features = [];

    /**
     * The taxonomies the post type has/belongs to
     * @var array
     */
    protected array $taxonomies = [];

    /**
     * The arguments for the post type
     * @var array
     */
    protected array $arguments = [];

    /**
     * @param string $name The name of the post type
     */
    public function __construct(string $name)
    {
        $this->name = Helpers::withPrefix($name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
        return array_replace([
            'name' => $this->pluralName,
            'singular_name' => $this->singularName,
            'menu_name' => $this->pluralName,
            'name_admin_bar' => $this->singularName,
            // translators: Label for a post type or taxonomy. %s: plural name of it
            'archives' => sprintf(__('%s archive', 'wordclass'), $this->pluralName),
            // translators: Label for a post type or taxonomy. %s: plural name of it
            'all_items' => sprintf(__('All %s', 'wordclass'), $this->pluralName),
            // translators: Label for a post type or taxonomy. %s: plural name of it
            'items_list' => sprintf(__('%s list', 'wordclass'), $this->pluralName),
            // translators: Label for a post type or taxonomy. %s: plural name of it
            'items_list_navigation' => sprintf(__('%s list navigation', 'wordclass'), $this->pluralName),
            // translators: Label for a post type or taxonomy. %s: plural name of it
            'filter_items_list' => sprintf(__('Filter %s list', 'wordclass'), $this->pluralName),
            // translators: Label for a post type or taxonomy. %s: singular name of it
            'parent_item_colon' => sprintf(__('Parent %s:', 'wordclass'), $this->singularName),
            // translators: Label for a post type or taxonomy. %s: singular name of it
            'add_new_item' => sprintf(__('Add new %s', 'wordclass'), $this->singularName),
            // translators: Label for a post type or taxonomy. %s: singular name of it
            'add_new' => sprintf(__('Add new %s', 'wordclass'), $this->singularName),
            // translators: Label for a post type or taxonomy. %s: singular name of it
            'new_item' => sprintf(__('New %s', 'wordclass'), $this->singularName),
            // translators: Label for a post type or taxonomy. %s: singular name of it
            'edit_item' => sprintf(__('Edit %s', 'wordclass'), $this->singularName),
            // translators: Label for a post type or taxonomy. %s: singular name of it
            'update_item' => sprintf(__('Update %s', 'wordclass'), $this->singularName),
            // translators: Label for a post type or taxonomy. %s: singular name of it
            'view_item' => sprintf(__('View %s', 'wordclass'), $this->singularName),
            // translators: Label for a post type or taxonomy. %s: singular name of it
            'search_items' => sprintf(__('Search %s', 'wordclass'), $this->singularName),
            // translators: Label for a post type or taxonomy. %s: singular name of it
            'insert_into_item' => sprintf(__('Insert into %s', 'wordclass'), $this->singularName),
            // translators: Label for a post type or taxonomy. %s: singular name of it
            'uploaded_to_this_item' => sprintf(__('Uploaded to this %s', 'wordclass'), $this->singularName),
            // translators: Label for a post type or taxonomy
            'not_found' => __('Not found', 'wordclass'),
            // translators: Label for a post type or taxonomy
            'not_found_in_trash' => __('Not found in trash', 'wordclass'),
            // translators: Label for a post type or taxonomy
            'featured_image' => __('Featured image', 'wordclass'),
            // translators: Label for a post type or taxonomy
            'set_featured_image' => __('Set featured image', 'wordclass'),
            // translators: Label for a post type or taxonomy
            'remove_featured_image' => __('Remove featured image', 'wordclass'),
            // translators: Label for a post type or taxonomy
            'use_featured_image' => __('Use as featured image', 'wordclass'),
        ], $this->labels);
    }

    /**
     * @param array $features
     * @return self
     */
    public function setFeatures(array $features): self
    {
        $this->features = $features;

        return $this;
    }

    /**
     * An array of strings and/or Taxonomy objects
     * @param  array $taxonomies
     * @return self
     */
    public function setTaxonomies(array $taxonomies): self
    {
        // Make sure the taxonomies are a string
        $this->taxonomies = array_map(
            fn ($taxonomy) => ($taxonomy instanceof Taxonomy)
                ? $taxonomy->getName()
                : $taxonomy,
            $taxonomies
        );

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
     * Get the default arguments, replaced with custom ones
     * @return array
     */
    public function getArguments(): array
    {
        return array_replace_recursive([
            'label' => $this->pluralName,
            'labels' => $this->getLabels(),
            'description' => $this->description,
            'supports' => $this->features,
            'taxonomies' => $this->taxonomies,
            'rewrite' => [
                'slug' => $this->getSlug(),
            ],
        ], $this->arguments);
    }

    /**
     * Register the post type
     * @return self
     */
    public function register(): self
    {
        add_action('init', function () {
            register_post_type($this->getName(), $this->getArguments());
        }, 10);

        return $this;
    }
}
