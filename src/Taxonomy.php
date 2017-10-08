<?php

namespace Wordclass;

use Wordclass\Utilities;

class Taxonomy {
    use Traits\CanSetTextDomain;
    use Traits\CanTranslate;



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
        $textDomain = static::textDomain();

        $this->_labels = array_replace_recursive([
            'name'                       => static::__($this->_name, $textDomain),
            'singular_name'              => static::__($this->_singularName, $textDomain),
            'menu_name'                  => static::__($this->_name, $textDomain),
            'all_items'                  => sprintf(static::__('All %s', $textDomain), $this->_name),
            'parent_item'                => sprintf(static::__('Parent %s', $textDomain), $this->_singularName),
            'parent_item_colon'          => sprintf(static::__('Parent %s:', $textDomain), $this->_singularName),
            'new_item_name'              => sprintf(static::__('New %s name', $textDomain), $this->_singularName),
            'add_new_item'               => sprintf(static::__('Add new %s', $textDomain), $this->_singularName),
            'edit_item'                  => sprintf(static::__('Edit %s', $textDomain), $this->_singularName),
            'update_item'                => sprintf(static::__('Update %s', $textDomain), $this->_singularName),
            'view_item'                  => sprintf(static::__('View %s', $textDomain), $this->_singularName),
            'separate_items_with_commas' => sprintf(static::__('Separate %s with commas', $textDomain), $this->_name),
            'add_or_remove_items'        => sprintf(static::__('Add or remove %s', $textDomain), $this->_name),
            'choose_from_most_used'      => static::__('Choose from the most used', $textDomain),
            'popular_items'              => sprintf(static::__('Popular %s', $textDomain), $this->_name),
            'search_items'               => sprintf(static::__('Search %s', $textDomain), $this->_name),
            'not_found'                  => static::__('Not found', $textDomain),
            'no_terms'                   => sprintf(static::__('No %s', $textDomain), $this->_name),
            'items_list'                 => sprintf(static::__('%s list', $textDomain), $this->_name),
            'items_list_navigation'      => sprintf(static::__('%s list navigation', $textDomain), $this->_name)
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
            'description'         => static::__($this->_description, static::textDomain()),
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
