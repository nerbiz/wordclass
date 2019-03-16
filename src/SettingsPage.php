<?php

namespace Nerbiz\Wordclass;

class SettingsPage
{
    /**
     * @var Init
     */
    protected $init;

    /**
     * The title of the settings page
     * @var string
     */
    protected $pageTitle = 'Theme settings';

    /**
     * The settings page slug, will be prepended with prefix
     * @var string
     */
    protected $pageSlug;

    /**
     * The group name of the settings, will be prepended with prefix
     * @var string
     */
    protected $settingsGroup = 'settings';

    /**
     * The icon of the menu item
     * @var string
     */
    protected $icon = 'dashicons-admin-settings';

    /**
     * The button position in the menu
     * @var int
     */
    protected $menuPosition;

    /**
     * Indicates whether the required scripts are added
     * Prevents including twice
     * @var bool
     */
    protected static $scriptsAdded = false;

    public function __construct()
    {
        $this->init = new Init();

        // Add the required scripts
        if (! static::$scriptsAdded) {
            $assets = Factory::make('Assets');
            $mediaUploadHandle = $this->init->getPrefix() . '-media-upload';
            $assets->addAdminJs([
                $mediaUploadHandle => $this->init->getVendorUri('nerbiz/wordclass/includes/js/media-upload.js')
            ]);

            static::$scriptsAdded = true;
        }
    }

    /**
     * @param $pageTitle
     * @return self
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    /**
     * @param  string $pageSlug
     * @return self
     */
    public function setPageSlug($pageSlug)
    {
        $this->pageSlug = $pageSlug;

        return $this;
    }

    /**
     * @param string $settingsGroup
     * @return self
     */
    public function setSettingsGroup($settingsGroup)
    {
        $this->settingsGroup = $settingsGroup;

        return $this;
    }

    /**
     * @param string $icon
     * @return self
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @param int $menuPosition
     * @return self
     */
    public function setMenuPosition($menuPosition)
    {
        $this->menuPosition = $menuPosition;

        return $this;
    }

    /**
     * Create an input[text] element, with current (escaped) value filled in
     * @param  array $arguments
     * @return string
     */
    protected function inputText(array $arguments)
    {
        return sprintf(
            '<input type="text" class="regular-text" name="%s" value="%s">',
            $arguments['name'],
            esc_attr(get_option($arguments['name']))
        );
    }

    /**
     * Create an input[checkbox] element, with current (escaped) value filled in
     * @param  array $arguments
     * @return string
     */
    protected function inputCheckbox(array $arguments)
    {
        return sprintf(
            '<input type="checkbox" name="%s" value="1" %s>',
            $arguments['name'],
            checked(1, get_option($arguments['name']), false)
        );
    }

    /**
     * Create an editor, with current (escaped) value filled in
     * @param  array $arguments
     * @return false|string
     */
    protected function inputEditor(array $arguments)
    {
        // Buffer the output, because wp_editor() echoes
        ob_start();

        wp_editor(
            apply_filters('the_content', get_option($arguments['name'])),
            $arguments['name'],
            [
                'wpautop'       => true,
                'media_buttons' => true,
                'textarea_name' => $arguments['name'],
                'editor_height' => 200
            ]
        );

        return ob_get_clean();
    }

    /**
     * Create a media select/upload element
     * @param array $arguments
     * @return string
     */
    protected function inputMedia(array $arguments)
    {
        wp_enqueue_media();

        $currentMediaUrl = trim(wp_get_attachment_image_url(get_option($arguments['name'])));

        return sprintf(
            '<div class="image-preview-wrapper">
                <img class="image-preview" src="%s" width="100" height="100">
            </div>
            <input type="button" class="button upload-media-button" value="%s" data-attachment-id="%s">
            <input type="hidden" name="%s" value="%s">',
            $currentMediaUrl,
            __('Select media', 'wordclass'),
            get_option($arguments['name']),
            $arguments['name'],
            get_option($arguments['name'])
        );
    }

    /**
     * Decide what kind of input field to create and echo it
     * @param  array $arguments 'type' and 'name' must be provided in this array
     * @return void
     * @throws \InvalidArgumentException If the required array key(s) are not set
     * @throws \Exception If the input type is not supported
     */
    public function decideInput(array $arguments)
    {
        if (! isset($arguments['type'], $arguments['name'])) {
            throw new \InvalidArgumentException(sprintf(
                "%s(): parameter 'arguments' needs to contain 'type' and 'name'",
                __METHOD__
            ));
        }

        // Construct the method name
        $type = ucfirst($arguments['type']);
        $methodName = 'input' . $type;

        if (method_exists($this, $methodName)) {
            echo $this->{$methodName}($arguments);
        } else {
            throw new \Exception(sprintf(
                "%s(): Unsupported input type '%s' requested",
                __METHOD__,
                is_object($arguments['type']) ? get_class($arguments['type']) : $arguments['type']
            ));
        }
    }

    /**
     * Add a settings section to the settings page
     * @param  string $id       Section ID, prefix will be prepended
     * @param  string $title
     * @param  string $subtitle
     * @param  array  $fields   Input fields for the settings, as name:options pairs
     * Fields:
     * title: the title of the input field
     * type: the type of the input field
     * @return self
     */
    public function addSection($id, $title, $subtitle = '', $fields = [])
    {
        add_action('admin_init', function () use ($id, $title, $subtitle, $fields) {
            $sectionId = $this->init->getPrefix() . '-' . $id;
            $pageSlug = $this->init->getPrefix() . '-' . $this->pageSlug;

            // Subtitle argument needs to be an echo'ing function
            $subtitle = function () use ($subtitle) {
                echo $subtitle;
            };

            // Add the section
            add_settings_section($sectionId, $title, $subtitle, $pageSlug);

            // Add the fields to the section
            foreach ($fields as $name => $options) {
                $nameHyphen = $this->init->getPrefix() . '-' . $name;
                $nameUnderscore = $this->init->getPrefix() . '_' . $name;

                // Register the setting name to the group
                $settingsGroup = $this->init->getPrefix() . '-' . $this->settingsGroup;
                register_setting($settingsGroup, $nameUnderscore);

                // Add the field for the setting
                add_settings_field($nameHyphen, $options['title'], [$this, 'decideInput'], $pageSlug, $sectionId, [
                    'type' => $options['type'],
                    'name' => $nameUnderscore
                ]);
            }
        });

        return $this;
    }

    /**
     * Add the settings page
     * @return void
     */
    public function create()
    {
        // Derive the page slug if it's not set yet
        if ($this->pageSlug === null) {
            $this->pageSlug = (new Utilities())->createSlug($this->pageTitle);
        }

        $pageSlug = $this->init->getPrefix() . '-' . $this->pageSlug;
        $settingsGroup = $this->init->getPrefix() . '-' . $this->settingsGroup;

        add_action('admin_menu', function () use ($pageSlug, $settingsGroup) {
            if (current_user_can('manage_options')) {
                $renderFunction = function () use ($pageSlug, $settingsGroup) {
                    echo '
                        <div class="wrap">
                            <h1>' . $this->pageTitle . '</h1>
                            <form action="options.php" method="POST">';

                    // Output nonce, action, and option_page fields for a settings page
                    settings_fields($settingsGroup);
                    // Print out all settings sections added to the settings page
                    do_settings_sections($pageSlug);
                    submit_button(__('Save settings', 'wordclass'));

                    echo '
                            </form>
                        </div>';
                };

                // Add the settings page
                add_menu_page(
                    $this->pageTitle, $this->pageTitle, 'manage_options', $pageSlug,
                    $renderFunction, $this->icon, $this->menuPosition
                );
            }
        }, 100);
    }
}
