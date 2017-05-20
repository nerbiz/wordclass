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
    private $_settingsGroup;
    private $_namePrefix = 'xx_';
    private $_idPrefix = 'xx-';



    /**
     * @see create()
     */
    public function __construct($title, $settingsgroup, $icon, $menuposition) {
        // Translate the title
        $this->_pageTitle = __($title, static::textDomain());

        // The page slug is the title converted to slug, by default
        $this->_pageSlug = Utilities::createSlug($title);

        // The group in which all settings go
        $this->_settingsGroup = $settingsgroup;

        add_action('admin_menu', function() use($icon, $menuposition) {
            if(current_user_can('manage_options')) {
                add_menu_page(
                    // Page title
                    __($this->_pageTitle, static::textDomain()),
                    // Menu title
                    __($this->_pageTitle, static::textDomain()),
                    // Capability
                    'manage_options',
                    // Menu slug
                    $this->_pageSlug,
                    // Output content
                    function() {
                        echo '
                            <div class="wrap">
                                <h1>' . __($this->_pageTitle, static::textDomain()) . '</h1>

                                <form action="options.php" method="POST">';
                                    // Output nonce, action, and option_page fields for a settings page
                                    settings_fields($this->_idPrefix . $this->_settingsGroup);
                                    // Print out all settings sections added to the settings page
                                    do_settings_sections($this->_pageSlug);
                                    submit_button('Opslaan');
                        echo '
                                </form>
                            </div>';
                    },
                    $icon,
                    $menuposition
                );
            }
        }, 100);

        return $this;
    }



    /**
     * Overwrite the page slug with a custom one
     * @param  String  $slug
     * @return $this
     */
    public function pageSlug($slug) {
        $this->_pageSlug = $slug;

        return $this;
    }



    /**
     * Set the prefix for all the input fields (name and ID) and the section IDs
     * @param String  $prefix
     * @return $this
     */
    public function prefix($prefix) {
        $this->_namePrefix = $prefix . '_';
        $this->_idPrefix = $prefix . '-';

        return $this;
    }



    /**
     * Create input elements of various types, with current (escaped) value filled in
     * @param  Array  $arguments  Options for the element
     */
    private function inputText($arguments) {
        return '<input type="text" class="regular-text" name="' . $arguments['name'] . '" value="' . esc_attr(get_option($arguments['name'])) . '">';
    }

    private function inputCheckbox($arguments) {
        return '<input type="checkbox" name="' . $arguments['name'] . '" value="1" ' . checked(1, get_option($arguments['name']), false) .'>';
    }

    private function inputWysiwyg($arguments) {
        // Buffer the output, because wp_editor() echoes
        ob_start();
        wp_editor(wp_kses_post(get_option($arguments['name'])), $arguments['name'], [
            'wpautop'       => true,
            'media_buttons' => true,
            'textarea_name' => $arguments['name'],
            'editor_height' => 200
        ]);
        return ob_get_clean();
    }



    /**
     * Decide what kind of input field to create
     * @param  Array  $arguments  'type' must be given in this array
     */
    public function decideInput($arguments) {
        $type = ucfirst($arguments['type']);
        echo $this->{'input' . $type}($arguments);
    }



    /**
     * Add a settings section to the settings page
     * @param String  $id        Section ID
     * @param String  $title     Section title
     * @param String  $subtitle  Function that echoes content between title and fields
     * @param Array   $fields    Input fields for the settings, as name:options pairs:
     *                             title: the title of the input field
     *                             type: the type of the input field
     * @return $this
     */
    public function addSection($id, $title, $subtitle='', $fields=[]) {
        add_action('admin_init', function() use($id, $title, $subtitle, $fields) {
            add_settings_section($this->_idPrefix.$id, __($title, static::textDomain()), function() use($subtitle) {
                echo __($subtitle, static::textDomain());
            }, $this->_pageSlug);

            foreach($fields as $name => $options) {
                register_setting($this->_idPrefix.$this->_settingsGroup, $this->_namePrefix.$name);

                add_settings_field(
                    // ID to identify the field
                    $this->_idPrefix.$name,
                    // Title of the setting
                    __($options['title'], static::textDomain()),
                    // Function that echoes the input field
                    [$this, 'decideInput'],
                    // Slug of the page to show this setting on
                    $this->_pageSlug,
                    // Slug of the section
                    $this->_idPrefix . $id,
                    // Arguments for the above function
                    [
                        'type' => $options['type'],
                        'name' => $this->_namePrefix.$name
                    ]
                );
            }
        });

        return $this;
    }



    /**
     * Initialize the creation chain
     * @param  String   $title          Title of the settings page
     * @param  String   $settingsgroup  The name of the group in which all settings go
     * @param  String   $icon           The name/URL/base64 of the icon
     * @param  Integer  $menuposition   Where the item appears in the menu
     * @return Object  An instance of this class
     */
    public static function create($title, $settingsgroup=null, $icon=null, $menuposition=null) {
        if($settingsgroup == null)
            $settingsgroup = 'settings';

        if($icon == null)
            $icon = 'dashicons-admin-settings';

        return new static($title, $settingsgroup, $icon, $menuposition);
    }
}    
