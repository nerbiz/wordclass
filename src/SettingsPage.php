<?php

namespace Nerbiz\Wordclass;

use Nerbiz\Wordclass\Traits\CanSetPrefix;

class SettingsPage
{
    use CanSetPrefix;

    /**
     * The title of the settings page
     * @var String
     */
    protected $pageTitle;

    /**
     * The settings page slug, will be prepended with prefix
     * @var String
     */
    protected $pageSlug;

    /**
     * The group name of the settings, will be prepended with prefix
     * @var String
     */
    protected $settingsGroup;

    /**
     * @see self::create()
     */
    protected function __construct($title, $settingsgroup, $icon, $menuposition)
    {
        $this->pageTitle = $title;

        // The page slug is the title converted to slug, by default
        $this->pageSlug = static::prefix() . '-' . Utilities::createSlug($title);

        // The group in which all settings go
        $this->settingsGroup = static::prefix() . '-' . $settingsgroup;

        add_action('admin_menu', function () use ($icon, $menuposition) {
            if (current_user_can('manage_options')) {
                add_menu_page(
                    // Page title
                    $this->pageTitle,
                    // Menu title
                    $this->pageTitle,
                    // Capability
                    'manage_options',
                    // Menu slug
                    $this->pageSlug,
                    // Output content
                    function () {
                        echo '
                            <div class="wrap">
                                <h1>' . $this->pageTitle . '</h1>
                                <form action="options.php" method="POST">';
                        // Output nonce, action, and option_page fields for a settings page
                        settings_fields($this->settingsGroup);
                        // Print out all settings sections added to the settings page
                        do_settings_sections($this->pageSlug);
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
     * @param  string  $slug
     * @return self
     */
    public function pageSlug($slug)
    {
        $this->pageSlug = static::prefix() . '-' . $slug;

        return $this;
    }

    /**
     * Create input elements of various types, with current (escaped) value filled in
     * @param  array  $arguments  Options for the element
     */
    protected function inputText($arguments)
    {
        return sprintf(
            '<input type="text" class="regular-text" name="%s" value="%s">',
            $arguments['name'],
            esc_attr(get_option($arguments['name']))
        );
    }

    protected function inputCheckbox($arguments)
    {
        return sprintf(
            '<input type="checkbox" name="%s" value="1" %s>',
            $arguments['name'],
            checked(1, get_option($arguments['name']), false)
        );
    }

    protected function inputWysiwyg($arguments)
    {
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
     * @param  array  $arguments  'type' must be given in this array
     */
    public function decideInput($arguments)
    {
        $type = ucfirst($arguments['type']);
        echo $this->{'input' . $type}($arguments);
    }

    /**
     * Add a settings section to the settings page
     * @param  string  $id        Section ID
     * @param  string  $title     Section title
     * @param  string  $subtitle  Function that echoes content between title and fields
     * @param  array   $fields    Input fields for the settings, as name:options pairs:
     *                             title: the title of the input field
     *                             type: the type of the input field
     * @return self
     */
    public function addSection($id, $title, $subtitle = '', $fields = [])
    {
        add_action('admin_init', function () use ($id, $title, $subtitle, $fields) {
            $prefix = static::prefix();
            $sectionId = $prefix . '-' . $id;

            add_settings_section(
                // ID to identify the section
                $sectionId,
                // Section title
                $title,
                // Section subtitle
                function () use ($subtitle) {
                    echo $subtitle;
                },
                // Slug of the settings page
                $this->pageSlug
            );

            foreach ($fields as $name => $options) {
                $nameHyphen = $prefix . '-' . $name;
                $nameUnderscore = $prefix . '_' . $name;

                register_setting(
                    $this->settingsGroup,
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
                    $this->pageSlug,
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
     * @param  string   $title          Title of the settings page
     * @param  string   $settingsgroup  The name of the group in which all settings go
     * @param  string   $icon           The name/URL/base64 of the icon
     * @param  int  $menuposition   Where the item appears in the menu
     * @return SettingsPage  An instance of this class
     */
    public static function create($title, $settingsgroup = null, $icon = null, $menuposition = null)
    {
        if ($settingsgroup === null) {
            $settingsgroup = 'settings';
        }

        if ($icon === null) {
            $icon = 'dashicons-admin-settings';
        }

        return new static($title, $settingsgroup, $icon, $menuposition);
    }
}
