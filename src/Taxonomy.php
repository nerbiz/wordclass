<?php

namespace Nerbiz\Wordclass;

class Taxonomy
{
    /**
     * Various Taxonomy properties
     * @var Mixed
     */
    protected $id;
    protected $name;
    protected $slug;
    protected $singularName;
    protected $description;

    /**
     * The labels for the taxonomy
     * @var Array
     */
    protected $labels = null;

    /**
     * The arguments for the taxonomy
     * @var Array
     */
    protected $arguments = null;

    /**
     * @see create()
     */
    protected function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Set the name of the taxonomy
     * The slug is derived from this name
     * The description is set using this value
     * Those can be changed set with other methods
     * @param  string  $name
     * @return self
     */
    public function name($name)
    {
        $this->name = $name;
        $this->slug = Utilities::createSlug($name);
        $this->description = 'Custom Taxonomy: ' . $name;

        return $this;
    }

    /**
     * Set the slug of the taxonomy
     * @param  string  $slug
     * @return self
     */
    public function slug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Set the singular name of the taxonomy
     * @param  string  $singular
     * @return self
     */
    public function singular($singular)
    {
        $this->singularName = $singular;

        return $this;
    }

    /**
     * Set the description of the CPT
     * @param  string  $description
     * @return self
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the post type(s) that this is a taxonomy of
     * @param  string|array $posttypes
     * @return self
     */
    public function forPostType($posttypes)
    {
        $posttypes = (array) $posttypes;

        // Make sure the types are strings
        // (could use __toString() method in case of an object)
        foreach ($posttypes as $key => $type) {
            $posttypes[$key] = (string) $type;
        }

        $this->postTypes = $posttypes;

        return $this;
    }

    /**
     * Set labels for the taxonomy
     * @param  array  $labels  (Optional) overwrite the default values (merged)
     * @return self;
     */
    public function labels($labels = [])
    {
        $this->labels = array_replace_recursive([
            'name'                       => $this->name,
            'singular_name'              => $this->singularName,
            'menu_name'                  => $this->name,
            'all_items'                  => sprintf(__('All %s', 'wordclass'), $this->name),
            'parent_item'                => sprintf(__('Parent %s', 'wordclass'), $this->singularName),
            'parent_item_colon'          => sprintf(__('Parent %s:', 'wordclass'), $this->singularName),
            'new_item_name'              => sprintf(__('New %s name', 'wordclass'), $this->singularName),
            'add_new_item'               => sprintf(__('Add new %s', 'wordclass'), $this->singularName),
            'edit_item'                  => sprintf(__('Edit %s', 'wordclass'), $this->singularName),
            'update_item'                => sprintf(__('Update %s', 'wordclass'), $this->singularName),
            'view_item'                  => sprintf(__('View %s', 'wordclass'), $this->singularName),
            'separate_items_with_commas' => sprintf(__('Separate %s with commas', 'wordclass'), $this->name),
            'add_or_remove_items'        => sprintf(__('Add or remove %s', 'wordclass'), $this->name),
            'choose_from_most_used'      => __('Choose from the most used', 'wordclass'),
            'popular_items'              => sprintf(__('Popular %s', 'wordclass'), $this->name),
            'search_items'               => sprintf(__('Search %s', 'wordclass'), $this->name),
            'not_found'                  => __('Not found', 'wordclass'),
            'no_terms'                   => sprintf(__('No %s', 'wordclass'), $this->name),
            'items_list'                 => sprintf(__('%s list', 'wordclass'), $this->name),
            'items_list_navigation'      => sprintf(__('%s list navigation', 'wordclass'), $this->name),
        ], $labels);

        return $this;
    }

    /**
     * Set arguments for the taxonomy
     * @param  array  $arguments  (Optional) overwrite the default values (merged)
     * @return self
     */
    public function arguments($arguments = [])
    {
        // Set the labels, if not set yet
        if ($this->labels === null) {
            $this->labels();
        }

        // Overwrite defaults, if arguments are given
        $this->arguments = array_replace_recursive([
            'labels'             => $this->labels,
            'description'        => $this->description,
            'hierarchical'       => true,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_nav_menus'  => true,
            'show_in_rest'       => false,
            'show_admin_column'  => true,
            'show_tagcloud'      => true,
            'capabilities'       => [],
            'rewrite'            => [
                'slug'         => $this->slug,
                'with_front'   => false,
                'hierarchical' => false,
            ],
        ], $arguments);

        return $this;
    }

    /**
     * Add the taxonomy
     * @return self
     */
    public function add()
    {
        // Set the arguments, if not set yet
        if (! $this->arguments) {
            $this->arguments();
        }

        add_action('init', function () {
            register_taxonomy($this->id, $this->postTypes, $this->arguments);
        }, 0);

        return $this;
    }

    /**
     * When echo'd, return the ID
     * @return string
     */
    public function __toString()
    {
        return $this->id;
    }

    /**
     * Initialize the creation chain
     * @param  string  $id  ID of the taxonomy
     * @return Taxonomy  An instance of this class
     */
    public static function create($id)
    {
        return new static($id);
    }
}
