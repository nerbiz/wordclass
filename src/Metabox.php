<?php

namespace Nerbiz\Wordclass;

use CMB2;

class Metabox
{
    /**
     * @var Init
     */
    protected $init;

    /**
     * The ID of the metabox
     * @var string
     */
    protected $id;

    /**
     * The title of the metabox
     * @var string
     */
    protected $title;

    /**
     * The post types this metabox applies to
     * @var array
     */
    protected $postTypes = [];

    /**
     * The options for the metabox
     * @var array
     */
    protected $options = [];

    /**
     * The fields for the metabox
     * @var array
     */
    protected $fields = [];

    public function __construct()
    {
        $this->init = new Init();
        require_once $this->init->getVendorPath('cmb2/cmb2/init.php');
    }

    /**
     * @param string $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string|array $postTypes
     * @return self
     */
    public function setPostTypes($postTypes)
    {
        $postTypes = (array) $postTypes;

        // Make sure the post types are a string
        foreach ($postTypes as $key => $type) {
            // Post type objects can be passed
            if ($type instanceof PostType) {
                $postTypes[$key] = $type->getId();
            } else {
                $postTypes[$key] = (string) $type;
            }
        }

        $this->postTypes = $postTypes;

        return $this;
    }

    /**
     * @param array $options
     * @return self
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get the default options, replaced with custom ones
     * @return array
     */
    public function getOptions()
    {
        return array_replace_recursive([
            /**
             * Required
             */
            // ID of the metabox
            'id'               => $this->init->getPrefix() . '-' . $this->id,
            // Title of the metabox
            'title'            => $this->title,
            // Post type(s)
            'object_types'     => $this->postTypes,

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
        ], $this->options);
    }

    /**
     * Add a field, that will be added to the metabox
     * @param  array $fieldProperties Properties for the field
     * @return self
     */
    public function addField(array $fieldProperties)
    {
        // Prefix the field ID
        $fieldProperties['id'] = $this->init->getPrefix() . '-' . $fieldProperties['id'];

        $this->fields[] = $fieldProperties;

        return $this;
    }

    /**
     * Create the metabox and set its fields
     * @return void
     */
    public function create()
    {
        add_action('cmb2_admin_init', function () {
            // Create the metabox
            $cmb = new CMB2($this->getOptions());

            // Add the fields to it
            foreach ($this->fields as $field) {
                $cmb->add_field($field);
            }
        });
    }
}
