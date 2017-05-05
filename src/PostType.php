<?php

namespace Wordclass;

use Wordclass\Utilities;

class PostType {
    use Traits\CanSetTextDomain;



    /**
     * Various CPT properties
     * @var Mixed
     */
    private $_id;
    private $_name;
    private $_slug;
    private $_singularName;
    private $_pluralName;
    private $_taxonomies = [];

    /**
     * The labels for the CPT
     * @var Array
     */
    private $_labels = null;

    /**
     * The arguments for the CPT
     * @var Array
     */
    private $_arguments = null;



    public function __construct($id) {
        $this->_id = $id;
    }



    /**
     * Set the name of the CPT
     * The plural name is implicitly the same
     * The slug is derived from this name
     * The description is set using this value
     * Those can be changed with other methods
     * @param  String  $name
     * @return $this
     */
    public function name($name) {
        $this->_name = $name;
        $this->_slug = Utilities::createSlug($name);
        $this->_pluralName = $name;
        $this->_description = 'Custom Post Type: ' . $name;

        return $this;
    }



    /**
     * Set the slug of the CPT
     * @param  String  $slug
     * @return $this
     */
    public function slug($slug) {
        $this->_slug = $slug;

        return $this;
    }



    /**
     * Set the singular name of the CPT
     * @param  String  $singular
     * @return $this
     */
    public function singular($singular) {
        $this->_singularName = $singular;

        return $this;
    }



    /**
     * Set the plural name of the CPT
     * @param  String  $plural
     * @return $this
     */
    public function plural($plural) {
        $this->_pluralName = $plural;

        return $this;
    }



    /**
     * Set the description of the CPT
     * @param  String  $description
     * @return $this
     */
    public function description($description) {
        $this->_description = $description;

        return $this;
    }



    /**
     * Set the taxonomies that the CPT has
     * @param  Array  $taxonomies
     * @return $this
     */
    public function taxonomies($taxonomies) {
        $this->_taxonomies = $taxonomies;

        return $this;
    }



    /**
     * Set labels for the CPT
     * @param  Array  $labels  (Optional) overwrite the default values (merged)
     * @return $this;
     */
    public function labels($labels=[]) {
        $this->_labels = array_replace_recursive([
            'name'                  => _x($this->_name, 'Post Type General Name', static::$_textDomain),
            'singular_name'         => _x($this->_singular, 'Post Type Singular Name', static::$_textDomain),
            'menu_name'             => __($this->_name, static::$_textDomain),
            'name_admin_bar'        => __($this->_pluralName, static::$_textDomain),
            'archives'              => __($this->_pluralName . ' Archive', static::$_textDomain),
            'parent_item_colon'     => __('Parent Item:', static::$_textDomain),
            'all_items'             => __('All Items', static::$_textDomain),
            'add_new_item'          => __('Add New Item', static::$_textDomain),
            'add_new'               => __('Add New', static::$_textDomain),
            'new_item'              => __('New Item', static::$_textDomain),
            'edit_item'             => __('Edit Item', static::$_textDomain),
            'update_item'           => __('Update Item', static::$_textDomain),
            'view_item'             => __('View Item', static::$_textDomain),
            'search_items'          => __('Search Item', static::$_textDomain),
            'not_found'             => __('Not found', static::$_textDomain),
            'not_found_in_trash'    => __('Not found in Trash', static::$_textDomain),
            'featured_image'        => __('Featured Image', static::$_textDomain),
            'set_featured_image'    => __('Set featured image', static::$_textDomain),
            'remove_featured_image' => __('Remove featured image', static::$_textDomain),
            'use_featured_image'    => __('Use as featured image', static::$_textDomain),
            'insert_into_item'      => __('Insert into item', static::$_textDomain),
            'uploaded_to_this_item' => __('Uploaded to this item', static::$_textDomain),
            'items_list'            => __('Items list', static::$_textDomain),
            'items_list_navigation' => __('Items list navigation', static::$_textDomain),
            'filter_items_list'     => __('Filter items list', static::$_textDomain)
        ], $labels);

        return $this;
    }



    /**
     * Set arguments for the CPT
     * @param  Array  $arguments  (Optional) overwrite the default values (merged)
     * @return $this
     */
    public function arguments($arguments=[]) {
        // Set the labels, if not set yet
        if($this->_labels == null)
            $this->labels();

        // Overwrite defaults, if arguments are given
        $this->_arguments = array_replace_recursive([
            'label'               => __($this->_name, static::$_textDomain),
            'description'         => __($this->_description, static::$_textDomain),
            'labels'              => $this->_labels,
            'supports'            => ['title', 'editor', 'thumbnail', 'page-attributes'],
            'taxonomies'          => $this->_taxonomies,
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
                'slug' => $this->_slug,
                'with_front' => false
            ]
        ], $arguments);

        return $this;
    }



    /**
     * Add the CPT
     * @return $this
     */
    public function add() {
        // Set the arguments, if not set yet
        if( ! $this->_arguments)
            $this->arguments();

        add_action('init', function() {
            register_post_type($this->_id, $this->_arguments);
        }, 0);

        return $this;
    }



    /**
     * When echo'd, return the ID
     * @return String
     */
    public function __toString() {
        return $this->_id;
    }



    /**
     * Initialize the creation chain
     * @param  String  $id  ID of the CPT
     * @return Object  An instance of this class
     */
    public static function create($id) {
        return new static($id);
    }
}
