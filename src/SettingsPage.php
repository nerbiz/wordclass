<?php

namespace Nerbiz\Wordclass;

use Nerbiz\Wordclass\InputFields\AbstractInputField;

class SettingsPage
{
    /**
     * The title of the settings page
     * @var string
     */
    protected $pageTitle = 'Theme settings';

    /**
     * The slug of the parent page, if this needs to be a subpage
     * @var string|null
     */
    protected $parentSlug = null;

    /**
     * The settings page slug, will be prepended with prefix
     * @var string
     */
    protected $pageSlug;

    /**
     * The group name of the settings
     * @var string|null
     */
    protected $settingsGroup = null;

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
     * @param string $pageTitle
     * @return self
     */
    public function setPageTitle(string $pageTitle): self
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    /**
     * @param string|null $parentSlug
     * @return self
     */
    public function setParentSlug(string $parentSlug): self
    {
        $this->parentSlug = $parentSlug;

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
     * Get the settings group name
     * @return string
     */
    public function getSettingsGroup(): string
    {
        if ($this->settingsGroup !== null) {
            return $this->settingsGroup;
        }

        return sprintf(
            '%s-settings-%s',
            Init::getPrefix(),
            (new Utilities())->createSlug($this->pageTitle)
        );
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
     * Add a settings section to the settings page
     * @param  string               $id       Section ID, prefix will be prepended
     * @param  string               $title
     * @param  string|null          $subtitle
     * @param  AbstractInputField[] $fields   Input fields for the settings
     * @return self
     */
    public function addSection(
        string $id,
        string $title,
        ?string $subtitle = null,
        array $fields = []
    ): self {
        add_action('admin_init', function () use ($id, $title, $subtitle, $fields) {
            $prefix = Init::getPrefix();
            $sectionId = $prefix . '-' . $id;
            $pageSlug = $prefix . '-' . $this->pageSlug;

            // Subtitle argument needs to be an echo'ing function
            $subtitle = function () use ($subtitle) {
                echo $subtitle;
            };

            // Add the section
            add_settings_section($sectionId, $title, $subtitle, $pageSlug);

            // Add the fields to the section
            foreach ($fields as $inputField) {
                // Register the setting name to the group
                register_setting(
                    $this->getSettingsGroup(),
                    $prefix . '_' . $inputField->getName()
                );

                // Add the field for the setting
                add_settings_field(
                    $prefix . '-' . $inputField->getName(),
                    $inputField->getTitle(),
                    function () use ($inputField) {
                        echo $inputField->render();
                    },
                    $pageSlug,
                    $sectionId
                );
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
        add_action('admin_menu', function () {
            // Derive the page slug if it's not set yet
            if ($this->pageSlug === null) {
                $this->pageSlug = (new Utilities())->createSlug($this->pageTitle);
            }

            $pageSlug = Init::getPrefix() . '-' . $this->pageSlug;
            $renderFunction = function () use ($pageSlug) {
                // For use in the template
                $settingsPage = $this;
                require __DIR__ . '/../includes/html/settings-page-template.php';
            };

            // Add the settings page
            if ($this->parentSlug !== null) {
                // As a subpage, if a parent slug is given
                add_submenu_page(
                    $this->parentSlug,
                    $this->pageTitle,
                    $this->pageTitle,
                    'manage_options',
                    $pageSlug,
                    $renderFunction
                );
            } else {
                // Or as a separate page
                add_menu_page(
                    $this->pageTitle,
                    $this->pageTitle,
                    'manage_options',
                    $pageSlug,
                    $renderFunction,
                    $this->icon,
                    $this->menuPosition
                );
            }
        }, 100);

        return $this;
    }
}
