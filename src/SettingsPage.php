<?php

namespace Nerbiz\Wordclass;

use Nerbiz\Wordclass\SettingInputs\SettingInputsManager;

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
     * @param string $pageTitle
     * @return self
     */
    public function setPageTitle(string $pageTitle): self
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    /**
     * @param  string $pageSlug
     * @return self
     */
    public function setPageSlug(string $pageSlug): self
    {
        $this->pageSlug = $pageSlug;

        return $this;
    }

    /**
     * @param string $settingsGroup
     * @return self
     */
    public function setSettingsGroup(string $settingsGroup): self
    {
        $this->settingsGroup = $settingsGroup;

        return $this;
    }

    /**
     * @param string $icon
     * @return self
     */
    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @param int $menuPosition
     * @return self
     */
    public function setMenuPosition(int $menuPosition): self
    {
        $this->menuPosition = $menuPosition;

        return $this;
    }

    /**
     * Echo HTML of an input element
     * @param  array $arguments 'type' and 'name' must be provided in this array
     * @return void
     * @throws \InvalidArgumentException If the required array key(s) are not set
     * @throws \Exception
     */
    public function renderInput(array $arguments): void
    {
        if (! isset($arguments['type'], $arguments['name'])) {
            throw new \InvalidArgumentException(sprintf(
                "%s(): parameter 'arguments' needs to contain 'type' and 'name'",
                __METHOD__
            ));
        }

        $settingInputsManager = new SettingInputsManager();
        $input = $settingInputsManager->getInput($arguments);
        echo $input->render();
    }

    /**
     * Add a settings section to the settings page
     * @param  string      $id       Section ID, prefix will be prepended
     * @param  string      $title
     * @param  string|null $subtitle
     * @param  array       $fields   Input fields for the settings, as name:options pairs
     * Fields:
     * title: the title of the input field
     * type: the type of the input field
     * @return self
     */
    public function addSection(
        string $id,
        string $title,
        ?string $subtitle = null,
        array $fields = []
    ): self {
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
                $options['name'] = $nameUnderscore;
                add_settings_field($nameHyphen, $options['title'], [$this, 'renderInput'],
                    $pageSlug, $sectionId, $options);
            }
        });

        return $this;
    }

    /**
     * Add the settings page
     * @return self
     */
    public function create(): self
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

        return $this;
    }
}
