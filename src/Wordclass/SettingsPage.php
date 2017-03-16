<?php

namespace Wordclass;

use Wordclass\Utilities;

class SettingsPage {
    use Traits\CanSetTextDomain;



    /**
     * Various settings page values
     * @var Mixed
     */
    private $_pageTitle;
    private $_pageSlug;
    private $_optionGroup;
    private $_inputPrefix;
    private $_inputIdPrefix;



    public function __construct($title, $slug) {
        // In order to make the title translatable, the text domain must first be set statically
        // After that, it can be set in the instance
        $this->textDomain(static::$_textDomainStatic);

        $this->_pageTitle = __($title, $this->_textDomain);
        $this->_pageSlug = $slug;
    }



    /**
     * Set the option group of the settings page
     * @param String  $name
     * @return $this
     */
    public function optionGroup($name) {
        $this->_optionGroup = $name;

        return $this;
    }



    /**
     * Set the prefix of all the input fields on the settings page
     * @param String  $prefix
     * @return $this
     */
    public function inputPrefix($prefix) {
        $this->_inputPrefix = $prefix;

        return $this;
    }



    /**
     * Set the prefix of all the input field IDs on the settings page
     * @param String  $prefix
     * @return $this
     */
    public function inputIdPrefix($prefix) {
        $this->_inputIdPrefix = $prefix;

        return $this;
    }



    /**
     * Add a settings page
     * @return $this
     */
    public function add() {
        add_action('admin_menu', function() {
            if(current_user_can('manage_options')) {
                add_options_page(
                    // Page title
                    __($this->_pageTitle, $this->_textDomain),
                    // Menu title
                    __($this->_pageTitle, $this->_textDomain),
                    // Capability
                    'manage_options',
                    // Menu slug
                    $this->_pageSlug,
                    // Output content
                    function() {
                        echo '
                            <div class="wrap">
                                <h1>' . __($this->_pageTitle, $this->_textDomain) . '</h1>

                                <form action="options.php" method="POST">';
                                    // Output nonce, action, and option_page fields for a settings page
                                    settings_fields($this->_optionGroup);
                                    // Print out all settings sections added to the settings page
                                    do_settings_sections($this->_pageSlug);
                                    submit_button('Opslaan');
                        echo '
                                </form>
                            </div>';
                    }
                );
            }
        }, 100);

        return $this;
    }



    /**
     * Create an input[text] element, with current (escaped) value filled in
     * @param  Array  $arguments  Options for the element: name
     */
    private function inputText($arguments) {
        echo '<input type="text" class="regular-text" name="' . $arguments['name'] . '" value="' . esc_attr(get_option($arguments['name'])) . '">';
    }



    /**
     * Decide what kind of input field to create
     * @param  Array  $arguments  'type' must be given in this array
     */
    public function decideInput($arguments) {
        $type = $arguments['type'];
        unset($arguments['type']);

        if($type == 'text')
            $this->inputText($arguments);
    }



    /**
     * Add a settings section to the settings page
     * @param String    $id        Section ID
     * @param String    $title     Section title
     * @param Callable  $subtitle  Function that echoes content between title and fields
     * @param Array     $fields    Input fields for the settings, as name:options pairs:
     *                               title: the title of the input field
     *                               type: the type of the input field
     * @return $this
     */
    public function addSection($id, $title, $subtitle=null, $fields=[]) {
        add_action('admin_init', function() use($id, $title, $subtitle, $fields) {
            add_settings_section($id, __($title, $this->_textDomain), $subtitle, $this->_pageSlug);

            foreach($fields as $name => $options) {
                register_setting($this->_optionGroup, $this->_inputPrefix.$name);
                add_settings_field(
                    // ID to identify the field
                    $this->_inputIdPrefix.$name,
                    __($options['title'], $this->_textDomain),
                    // Function that echoes the input field
                    [$this, 'decideInput'],
                    $this->_pageSlug,
                    $id,
                    // Arguments for the above function
                    [
                        'type' => $options['type'],
                        'name' => $this->_inputPrefix.$name
                    ]
                );
            }
        });

        return $this;
    }



    /**
     * Initialize the creation chain
     * @param  String $title  Title of the settings page
     * @param  String $slug   (Optional) explicit page slug, will otherwise be derived from title
     * @return Object  An instance of this class
     */
    public static function create($title, $slug=null) {
        if($slug == null)
            $slug = Utilities::createSlug($title);

        return new static($title, $slug);
    }
}    
