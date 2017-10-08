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
        $textDomain = static::textDomain();

        $this->_labels = array_replace_recursive([
            'name'                  => static::__($this->_name, $textDomain),
            'singular_name'         => static::__($this->_singularName, $textDomain),
            'menu_name'             => static::__($this->_name, $textDomain),
            'name_admin_bar'        => static::__($this->_singularName, $textDomain),
            'archives'              => sprintf(static::__('%s archive', $textDomain), $this->_name),
            'parent_item_colon'     => sprintf(static::__('Parent %s:', $textDomain), $this->_singularName),
            'all_items'             => sprintf(static::__('All %s', $textDomain), $this->_name),
            'add_new_item'          => sprintf(static::__('Add new %s', $textDomain), $this->_singularName),
            'add_new'               => sprintf(static::__('Add new %s', $textDomain), $this->_singularName),
            'new_item'              => sprintf(static::__('New %s', $textDomain), $this->_singularName),
            'edit_item'             => sprintf(static::__('Edit %s', $textDomain), $this->_singularName),
            'update_item'           => sprintf(static::__('Update %s', $textDomain), $this->_singularName),
            'view_item'             => sprintf(static::__('View %s', $textDomain), $this->_singularName),
            'search_items'          => sprintf(static::__('Search %s', $textDomain), $this->_singularName),
            'not_found'             => static::__('Not found', $textDomain),
            'not_found_in_trash'    => static::__('Not found in trash', $textDomain),
            'featured_image'        => static::__('Featured image', $textDomain),
            'set_featured_image'    => static::__('Set featured image', $textDomain),
            'remove_featured_image' => static::__('Remove featured image', $textDomain),
            'use_featured_image'    => static::__('Use as featured image', $textDomain),
            'insert_into_item'      => sprintf(static::__('Insert into %s', $textDomain), $this->_singularName),
            'uploaded_to_this_item' => sprintf(static::__('Uploaded to this %s', $textDomain), $this->_singularName),
            'items_list'            => sprintf(static::__('%s list', $textDomain), $this->_name),
            'items_list_navigation' => sprintf(static::__('%s list navigation', $textDomain), $this->_name),
            'filter_items_list'     => sprintf(static::__('Filter %s list', $textDomain), $this->_name)
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
