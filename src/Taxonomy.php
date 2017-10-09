<?php

namespace Wordclass;

use Wordclass\Utilities;

class Taxonomy {
    /**
     * Various Taxonomy properties
     * @var Mixed
     */
    private $_id;
    private $_name;
    private $_slug;
    private $_singularName;
    private $_description;

    /**
     * The labels for the taxonomy
     * @var Array
     */
    private $_labels = null;

    /**
     * The arguments for the taxonomy
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
     * Set the name of the taxonomy
     * The slug is derived from this name
     * The description is set using this value
     * Those can be changed set with other methods
     * @param  String  $name
     * @return $this
     */
    public function name($name) {
        $this->_name = $name;
        $this->_slug = Utilities::createSlug($name);
        $this->_description = 'Custom Taxonomy: ' . $name;

        return $this;
    }



    /**
     * Set the slug of the taxonomy
     * @param  String  $slug
     * @return $this
     */
    public function slug($slug) {
        $this->_slug = $slug;

        return $this;
    }



    /**
     * Set the singular name of the taxonomy
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
     * Set the post type(s) that this is a taxonomy of
     * @param  String|Array $posttypes
     * @return $this
     */
    public function forPostType($posttypes) {
        $posttypes = (array) $posttypes;

        // Make sure the types are strings
        // (could use __toString() method in case of an object)
        foreach($posttypes as $key => $type)
            $posttypes[$key] = (string) $type;

        $this->_postTypes = $posttypes;

        return $this;
    }



    /**
     * Set labels for the taxonomy
     * @param  Array  $labels  (Optional) overwrite the default values (merged)
     * @return $this;
     */
    public function labels($labels=[]) {
        $this->_labels = array_replace_recursive([
            'name'                       => $this->_name,
            'singular_name'              => $this->_singularName,
            'menu_name'                  => $this->_name,
            'all_items'                  => sprintf(__('All %s', 'wordclass'), $this->_name),
            'parent_item'                => sprintf(__('Parent %s', 'wordclass'), $this->_singularName),
            'parent_item_colon'          => sprintf(__('Parent %s:', 'wordclass'), $this->_singularName),
            'new_item_name'              => sprintf(__('New %s name', 'wordclass'), $this->_singularName),
            'add_new_item'               => sprintf(__('Add new %s', 'wordclass'), $this->_singularName),
            'edit_item'                  => sprintf(__('Edit %s', 'wordclass'), $this->_singularName),
            'update_item'                => sprintf(__('Update %s', 'wordclass'), $this->_singularName),
            'view_item'                  => sprintf(__('View %s', 'wordclass'), $this->_singularName),
            'separate_items_with_commas' => sprintf(__('Separate %s with commas', 'wordclass'), $this->_name),
            'add_or_remove_items'        => sprintf(__('Add or remove %s', 'wordclass'), $this->_name),
            'choose_from_most_used'      => __('Choose from the most used', 'wordclass'),
            'popular_items'              => sprintf(__('Popular %s', 'wordclass'), $this->_name),
            'search_items'               => sprintf(__('Search %s', 'wordclass'), $this->_name),
            'not_found'                  => __('Not found', 'wordclass'),
            'no_terms'                   => sprintf(__('No %s', 'wordclass'), $this->_name),
            'items_list'                 => sprintf(__('%s list', 'wordclass'), $this->_name),
            'items_list_navigation'      => sprintf(__('%s list navigation', 'wordclass'), $this->_name)
        ], $labels);

        return $this;
    }



    /**
     * Set arguments for the taxonomy
     * @param  Array  $arguments  (Optional) overwrite the default values (merged)
     * @return $this
     */
    public function arguments($arguments=[]) {
        // Set the labels, if not set yet
        if($this->_labels == null)
            $this->labels();

        // Overwrite defaults, if arguments are given
        $this->_arguments = array_replace_recursive([
            'labels'             => $this->_labels,
            'description'         => $this->_description,
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
                'slug'         => $this->_slug,
                'with_front'   => false,
                'hierarchical' => false
            ]
        ], $arguments);

        return $this;
    }



    /**
     * Add the taxonomy
     * @return $this
     */
    public function add() {
        // Set the arguments, if not set yet
        if( ! $this->_arguments)
            $this->arguments();

        add_action('init', function() {
            register_taxonomy($this->_id, $this->_postTypes, $this->_arguments);
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
     * @param  String  $id  ID of the taxonomy
     * @return Object  An instance of this class
     */
    public static function create($id) {
        return new static($id);
    }
}
