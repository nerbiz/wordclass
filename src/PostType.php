<?php

namespace Wordclass;

use Wordclass\Utilities;

class PostType {
    use Traits\CanSetTextDomain;
    use Traits\CanTranslate;



    /**
     * Various CPT properties
     * @var Mixed
     */
    private $_id;
    private $_name;
    private $_slug;
    private $_singularName;
    private $_description;
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



    /**
     * @see create()
     */
    private function __construct($id) {
        $this->_id = $id;
    }



    /**
     * Set the name of the CPT
     * The slug is derived from this name
     * The description is set using this value
     * Those can be changed with other methods
     * @param  String  $name
     * @return $this
     */
    public function name($name) {
        $this->_name = $name;
        $this->_slug = Utilities::createSlug($name);
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
        $this->_taxonomies = (array) $taxonomies;

        return $this;
    }



    /**
     * Set labels for the CPT
     * @param  Array  $labels  (Optional) overwrite the default values (merged)
     * @return $this;
     */
    public function labels($labels=[]) {
        $this->_labels = array_replace_recursive([
            'name'                  => static::__($this->_name, static::textDomain()),
            'singular_name'         => static::__($this->_singularName, static::textDomain()),
            'menu_name'             => static::__($this->_name, static::textDomain()),
            'name_admin_bar'        => static::__($this->_singularName, static::textDomain()),
            'archives'              => static::__($this->_name . ' Archive', static::textDomain()),
            'parent_item_colon'     => static::__('Parent ' . $this->_singularName . ':', static::textDomain()),
            'all_items'             => static::__('All ' . $this->_name, static::textDomain()),
            'add_new_item'          => static::__('Add new ' . $this->_singularName, static::textDomain()),
            'add_new'               => static::__('Add new ' . $this->_singularName, static::textDomain()),
            'new_item'              => static::__('New ' . $this->_singularName, static::textDomain()),
            'edit_item'             => static::__('Edit ' . $this->_singularName, static::textDomain()),
            'update_item'           => static::__('Update ' . $this->_singularName, static::textDomain()),
            'view_item'             => static::__('View ' . $this->_singularName, static::textDomain()),
            'search_items'          => static::__('Search ' . $this->_singularName, static::textDomain()),
            'not_found'             => static::__('Not found', static::textDomain()),
            'not_found_in_trash'    => static::__('Not found in trash', static::textDomain()),
            'featured_image'        => static::__('Featured image', static::textDomain()),
            'set_featured_image'    => static::__('Set featured image', static::textDomain()),
            'remove_featured_image' => static::__('Remove featured image', static::textDomain()),
            'use_featured_image'    => static::__('Use as featured image', static::textDomain()),
            'insert_into_item'      => static::__('Insert into ' . $this->_singularName, static::textDomain()),
            'uploaded_to_this_item' => static::__('Uploaded to this ' . $this->_singularName, static::textDomain()),
            'items_list'            => static::__($this->_name . ' list', static::textDomain()),
            'items_list_navigation' => static::__($this->_name . ' list navigation', static::textDomain()),
            'filter_items_list'     => static::__('Filter ' . $this->_name . ' list', static::textDomain())
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
            'label'               => static::__($this->_name, static::textDomain()),
            'description'         => static::__($this->_description, static::textDomain()),
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
                'slug'       => $this->_slug,
                'with_front' => false,
                'feeds'      => true,
                'pages'      => true
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
