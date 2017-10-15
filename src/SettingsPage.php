<?php

namespace Wordclass;

use Wordclass\Utilities;

class SettingsPage {
    use Traits\CanSetPrefix;



    /**
     * The title of the settings page
     * @var String
     */
    private $_pageTitle;

    /**
     * The settings page slug, will be prepended with prefix
     * @var String
     */
    private $_pageSlug;

    /**
     * The group name of the settings, will be prepended with prefix
     * @var String
     */
    private $_settingsGroup;



    /**
     * @see create()
     */
    private function __construct($title, $settingsgroup, $icon, $menuposition) {
        $this->_pageTitle = $title;

        // The page slug is the title converted to slug, by default
        $this->_pageSlug = static::prefix() . '-' . Utilities::createSlug($title);

        // The group in which all settings go
        $this->_settingsGroup = static::prefix() . '-' . $settingsgroup;

        add_action('admin_menu', function() use($icon, $menuposition) {
            if(current_user_can('manage_options')) {
                add_menu_page(
                    // Page title
                    $this->_pageTitle,
                    // Menu title
                    $this->_pageTitle,
                    // Capability
                    'manage_options',
                    // Menu slug
                    $this->_pageSlug,
                    // Output content
                    function() {
                        echo '
                            <div class="wrap">
                                <h1>' . $this->_pageTitle . '</h1>

                                <form action="options.php" method="POST">';
                                    // Output nonce, action, and option_page fields for a settings page
                                    settings_fields($this->_settingsGroup);
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
     * Prefix will be prepended
     * @param  String  $slug
     * @return $this
     */
    public function pageSlug($slug) {
        $this->_pageSlug = static::prefix() . '-' . $slug;

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
            $prefix = static::prefix();
            $sectionId = $prefix . '-' . $id;

            add_settings_section(
                // ID to identify the section
                $sectionId,
                // Section title
                $title,
                // Section subtitle
                function() use($subtitle) {
                    echo $subtitle;
                },
                // Slug of the settings page
                $this->_pageSlug
            );

            foreach($fields as $name => $options) {
                $nameHyphen = $prefix . '-' . $name;
                $nameUnderscore = $prefix . '_' . $name;

                register_setting(
                    $this->_settingsGroup,
                    $nameUnderscore
                );

                add_settings_field(
                    // ID to identify the field
                    $nameHyphen,
                    // Title of the setting
                    $options['title'],
                    // Function that echoes the input field
                    [$this, 'decideInput'],
                    // Slug of the page to show this setting on
                    $this->_pageSlug,
                    // Slug (ID) of the section
                    $sectionId,
                    // Arguments for the above function
                    [
                        'type' => $options['type'],
                        'name' => $nameUnderscore
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
        if($settingsgroup === null)
            $settingsgroup = 'settings';

        if($icon === null)
            $icon = 'dashicons-admin-settings';

        return new static($title, $settingsgroup, $icon, $menuposition);
    }
}    
