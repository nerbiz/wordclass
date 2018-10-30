<?php

namespace Nerbiz\Wordclass;

class PostType
{
    /**
     * Various CPT properties
     * @var Mixed
     */
    protected $id;
    protected $name;
    protected $slug;
    protected $singularName;
    protected $description;
    protected $taxonomies = [];

    /**
     * The labels for the CPT
     * @var Array
     */
    protected $labels = null;

    /**
     * The arguments for the CPT
     * @var Array
     */
    protected $arguments = null;

    /**
     * @see self::create()
     */
    protected function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Set the name of the CPT
     * The slug is derived from this name
     * The description is set using this value
     * Those can be changed with other methods
     * @param  string  $name
     * @return self
     */
    public function name($name)
    {
        $this->name = $name;
        $this->slug = Utilities::createSlug($name);
        $this->description = 'Custom Post Type: ' . $name;

        return $this;
    }

    /**
     * Set the slug of the CPT
     * @param  string  $slug
     * @return self
     */
    public function slug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Set the singular name of the CPT
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
     * Set the taxonomies that the CPT has
     * @param  array  $taxonomies
     * @return self
     */
    public function taxonomies($taxonomies)
    {
        $this->taxonomies = (array) $taxonomies;

        return $this;
    }

    /**
     * Set labels for the CPT
     * @param  array  $labels  (Optional) overwrite the default values (merged)
     * @return self;
     */
    public function labels($labels = [])
    {
        $this->labels = array_replace_recursive([
            'name'                  => $this->name,
            'singular_name'         => $this->singularName,
            'menu_name'             => $this->name,
            'name_admin_bar'        => $this->singularName,
            'archives'              => sprintf(__('%s archive', 'wordclass'), $this->name),
            'parent_item_colon'     => sprintf(__('Parent %s:', 'wordclass'), $this->singularName),
            'all_items'             => sprintf(__('All %s', 'wordclass'), $this->name),
            'add_new_item'          => sprintf(__('Add new %s', 'wordclass'), $this->singularName),
            'add_new'               => sprintf(__('Add new %s', 'wordclass'), $this->singularName),
            'new_item'              => sprintf(__('New %s', 'wordclass'), $this->singularName),
            'edit_item'             => sprintf(__('Edit %s', 'wordclass'), $this->singularName),
            'update_item'           => sprintf(__('Update %s', 'wordclass'), $this->singularName),
            'view_item'             => sprintf(__('View %s', 'wordclass'), $this->singularName),
            'search_items'          => sprintf(__('Search %s', 'wordclass'), $this->singularName),
            'not_found'             => __('Not found', 'wordclass'),
            'not_found_in_trash'    => __('Not found in trash', 'wordclass'),
            'featured_image'        => __('Featured image', 'wordclass'),
            'set_featured_image'    => __('Set featured image', 'wordclass'),
            'remove_featured_image' => __('Remove featured image', 'wordclass'),
            'use_featured_image'    => __('Use as featured image', 'wordclass'),
            'insert_into_item'      => sprintf(__('Insert into %s', 'wordclass'), $this->singularName),
            'uploaded_to_this_item' => sprintf(__('Uploaded to this %s', 'wordclass'), $this->singularName),
            'items_list'            => sprintf(__('%s list', 'wordclass'), $this->name),
            'items_list_navigation' => sprintf(__('%s list navigation', 'wordclass'), $this->name),
            'filter_items_list'     => sprintf(__('Filter %s list', 'wordclass'), $this->name),
        ], $labels);

        return $this;
    }

    /**
     * Set arguments for the CPT
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
            'label'               => $this->name,
            'description'         => $this->description,
            'labels'              => $this->labels,
            'supports'            => ['title', 'editor', 'thumbnail', 'page-attributes'],
            'taxonomies'          => $this->taxonomies,
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'page',
            'rewrite'             => [
                'slug'       => $this->slug,
                'with_front' => false,
                'feeds'      => true,
                'pages'      => true
            ],
        ], $arguments);

        return $this;
    }

    /**
     * Add the CPT
     * @return self
     */
    public function add()
    {
        // Set the arguments, if not set yet
        if (! $this->arguments) {
            $this->arguments();
        }

        add_action('init', function () {
            register_post_type($this->id, $this->arguments);
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
     * @param  string  $id  ID of the CPT
     * @return PostType  An instance of this class
     */
    public static function create($id)
    {
        return new static($id);
    }
}
