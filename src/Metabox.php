<?php

namespace Wordclass;

require_once __DIR__ . '/../../../webdevstudios/cmb2/init.php';

use CMB2;

class Metabox {
    use Traits\CanSetPrefix;



    /**
     * The options for the metabox
     * @var Array
     */
    private $_options;

    /**
     * The fields for the metabox
     * @var Array
     */
    private $_fields = [];



    /**
     * @see create()
     */
    private function __construct($id, $title, $posttypes, $options=[]) {
        $this->_options = array_replace_recursive([
            /**
             * Required
             */
            // ID of the metabox
            'id'               => static::prefix() . '-' . $id,
            // Title of the metabox
            'title'            => $title,
            // Post type(s)
            'object_types'     => $posttypes,

            /**
             * Optional
             */
            'context'          => 'normal',
            'priority'         => 'high',
            // Show field names on the left
            'show_names'       => true,
            // Callback to determine if metabox should display
            'show_on_cb'       => null,
            // Post IDs or page templates to display this metabox. overrides 'show_on_cb'
            'show_on'          => [],
            // Include CMB2 stylesheet
            'cmb_styles'       => true,
            // Include CMB2 JS
            'enqueue_js'       => true,
            'fields'           => [],
            'hookup'           => true,
            // Will not save during hookup if false
            'save_fields'      => true,
            // Default to metabox being closed?
            'closed'           => false,
            'taxonomies'       => [],
            // or 'add-existing-user'
            'new_user_section' => 'add-new-user',
            'new_term_section' => true,
            'show_in_rest'     => false,
            // Extra cmb2-wrap classes
            'classes'          => null,
            // Add classes through a callback
            'classes_cb'       => null
        ], $options);
    }



    /**
     * Add a field, that will be added to the metabox
     * This is simply a wrapper, which allows chaining
     * @param Array  $options  Options for the field
     * @return $this
     */
    public function addField($options) {
        $this->_fields[] = $options;

        return $this;
    }



    /**
     * Create the metabox and set its fields
     */
    public function add() {
        add_action('cmb2_admin_init', function() {
            // Create the metabox
            $cmb = new CMB2($this->_options);

            // Add the fields to it
            foreach($this->_fields as $options)
                $cmb->add_field($options);
        });
    }



    /**
     * Create a new CMB2 instance
     * @param  String        $id         Metabox ID
     * @param  String        $title      Metabox title
     * @param  Array|String  $posttypes  1 or more posttypes to register the metabox to
     * @param  Array         $options    Options for the object (merged with defaults)
     * @return Object  An instance of this class
     */
    public static function create($id, $title, $posttypes, $options=[]) {
        $posttypes = (array) $posttypes;

        // Make sure the post types are a string (could use __toString() of an object)
        foreach($posttypes as $key => $type)
            $posttypes[$key] = (string) $type;

        return new static($id, $title, $posttypes, $options);
    }
}
