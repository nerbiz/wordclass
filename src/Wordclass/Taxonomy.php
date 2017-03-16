<?php

namespace Wordclass;

use Wordclass\Utilities;

class Taxonomy {
    use Traits\CanSetTextDomain;



    /**
     * Various Taxonomy properties
     * @var Mixed
     */
    private $_id;
    private $_name;
    private $_slug;
    private $_singularName;
    private $_pluralName;

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



    public function __construct($id) {
        $this->_id = $id;
    }



    /**
     * Set the name of the taxonomy
     * The plural name is implicitly the same
     * The slug is derived from this name
     * The description is set using this value
     * Those can be changed set with other methods
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
     * Set the plural name of the taxonomy
     * @param  String  $plural
     * @return $this
     */
    public function plural($plural) {
        $this->_pluralName = $plural;

        return $this;
    }



    /**
     * Set the post types that this is a taxonomy of
     * @param  String|Array $posttypes
     * @return $this
     */
    public function forPostTypes($posttypes) {
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
            'name'                       => _x($this->_name, 'Taxonomy General Name', $this->_textDomain),
            'singular_name'              => _x($this->_singularName, 'Taxonomy Singular Name', $this->_textDomain),
            'menu_name'                  => __($this->_name, $this->_textDomain),
            'all_items'                  => __('All Items', $this->_textDomain),
            'parent_item'                => __('Parent Item', $this->_textDomain),
            'parent_item_colon'          => __('Parent Item:', $this->_textDomain),
            'new_item_name'              => __('New Item Name', $this->_textDomain),
            'add_new_item'               => __('Add New Item', $this->_textDomain),
            'edit_item'                  => __('Edit Item', $this->_textDomain),
            'update_item'                => __('Update Item', $this->_textDomain),
            'view_item'                  => __('View Item', $this->_textDomain),
            'separate_items_with_commas' => __('Separate items with commas', $this->_textDomain),
            'add_or_remove_items'        => __('Add or remove items', $this->_textDomain),
            'choose_from_most_used'      => __('Choose from the most used', $this->_textDomain),
            'popular_items'              => __('Popular Items', $this->_textDomain),
            'search_items'               => __('Search Items', $this->_textDomain),
            'not_found'                  => __('Not Found', $this->_textDomain),
            'no_terms'                   => __('No items', $this->_textDomain),
            'items_list'                 => __('Items list', $this->_textDomain),
            'items_list_navigation'      => __('Items list navigation', $this->_textDomain),
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
            'labels'            => $this->_labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true,
            'rewrite'           => [
                'slug' => $this->_slug,
                'with_front' => false,
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
     * Return this instance
     * @return $this
     */
    public function get() {
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



    /**
     * Get the slug of a taxonomy
     * @param  String|null  $name  null gets this slug, string: gets slug of given taxonomy
     * @return String
     */
    public function getSlug($name=null) {
        if($name == null)
            return $this->_slug;

        else {
            $taxonomy = get_taxonomies(['name' => $name], 'objects');
            return $taxonomy[$name]->rewrite['slug'];
        }
    }
}
