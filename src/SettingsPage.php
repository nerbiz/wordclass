<?php

namespace Nerbiz\WordClass;

class SettingsPage
{
    /**
     * The title of the settings page
     * @var string
     */
    protected string $pageTitle = 'Theme settings';

    /**
     * The settings page slug, will be prepended with prefix
     * @var string
     */
    protected string $pageSlug;

    /**
     * The slug of the parent page, if this needs to be a subpage
     * @var string|null
     */
    protected ?string $parentSlug = null;

    /**
     * The unique name of the submit butten
     * @var string
     */
    protected string $submitButtonName;

    /**
     * The capability required for using the settings page
     * @var string
     */
    protected string $capability = 'manage_options';

    /**
     * The icon of the menu item
     * @var string
     */
    protected string $icon = 'dashicons-admin-settings';

    /**
     * The button position in the menu
     * @var int|null
     */
    protected ?int $menuPosition = null;

    /**
     * The sections of the settings page
     * @var SettingsPageSection[]
     */
    protected array $sections = [];

    public function __construct(?string $pageTitle = null)
    {
        $this->pageTitle = $pageTitle ?? __('Theme settings');

        // Derive the page slug from the title
        $this->setPageSlug(Utilities::createSlug($this->getPageTitle()));
    }

    /**
     * @return string
     */
    public function getPageTitle(): string
    {
        return $this->pageTitle;
    }

    /**
     * @return string
     */
    public function getPageSlug(): string
    {
        return $this->pageSlug;
    }

    /**
     * @param string $pageSlug
     * @return self
     */
    public function setPageSlug(string $pageSlug): self
    {
        $this->pageSlug = $pageSlug;

        // Derive the submit button name from the page slug
        $this->setSubmitButtonName(sprintf(
            'submit-settings-%s',
            $this->pageSlug
        ));

        return $this;
    }

    /**
     * @return string|null
     */
    public function getParentSlug(): ?string
    {
        return $this->parentSlug;
    }

    /**
     * @param string $parentSlug
     * @return self
     */
    public function setParentSlug(string $parentSlug): self
    {
        $this->parentSlug = $parentSlug;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubmitButtonName(): string
    {
        return $this->submitButtonName;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setSubmitButtonName(string $name): self
    {
        $this->submitButtonName = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getCapability(): string
    {
        return $this->capability;
    }

    /**
     * @param string $capability
     * @return self
     */
    public function setCapability(string $capability): self
    {
        $this->capability = $capability;

        return $this;
    }

    /**
     * Set the capability based on a role
     * @param string $role
     * @return self
     */
    public function setCapabilityByRole(string $role): self
    {
        switch ($role) {
            case 'superadmin':
            case 'super admin':
            case 'super-admin':
                $this->setCapability('manage_sites');
                break;
            case 'admin':
            case 'administrator':
                $this->setCapability('manage_options');
                break;
            case 'editor':
                $this->setCapability('edit_pages');
                break;
            case 'author':
                $this->setCapability('publish_posts');
                break;
            case 'contributor':
                $this->setCapability('edit_posts');
                break;
            case 'subscriber':
                $this->setCapability('read');
                break;
            default:
                $this->setCapability('manage_options');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
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
     * @return int|null
     */
    public function getMenuPosition(): ?int
    {
        return $this->menuPosition;
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
     * @param SettingsPageSection $section
     * @return self
     */
    public function addSection(SettingsPageSection $section): self
    {
        // Use the section ID as the input field name prefix
        foreach ($section->getFields() as $field) {
            $currentPrefix = trim($field->getNamePrefix());
            if ($currentPrefix === '') {
                $field->setNamePrefix($section->getId());
            }
        }

        $this->sections[] = $section;

        return $this;
    }

    /**
     * @return SettingsPageSection[]
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * Get the nonce name used for validation
     * @return string
     */
    public function getNonceName(): string
    {
        $nonceName = sprintf('%s_%s_nonce', Init::getPrefix(), $this->getPageSlug());
        return str_replace('-', '_', $nonceName);
    }

    /**
     * Store submitted values
     * @return void
     */
    protected function storeValues(): void
    {
        // Return when POST is empty
        if (count($_POST) < 1) {
            return;
        }

        // Check if the current user is allowed to update the values
        if (! current_user_can($this->getCapability())) {
            wp_die(__("You don't have the right permissions to update these settings.", 'wordclass'));
        }

        // Check if the nonce is valid
        if (! wp_verify_nonce($_POST[$this->getNonceName()] ?? '')) {
            wp_die(__('Invalid nonce value, please refresh the page and try again.', 'wordclass'));
        }

        // Strip slashes
        $_POST = wp_unslash($_POST);

        // Store all submitted values
        foreach ($this->sections as $section) {
            foreach ($section->getFields() as $field) {
                $name = $field->getFullName();

                if (isset($_POST[$name])) {
                    update_option($name, $_POST[$name]);
                }
            }
        }

        // Show the default 'Settings saved' message
        add_settings_error('general', 'settings_updated', __('Settings saved.'), 'updated');
    }

    /**
     * Add the settings page
     * @return self
     */
    public function create(): self
    {
        add_action('admin_menu', function () {
            // Ensure creation of page slug (and submit button name)
            $pageSlug = Init::getPrefix() . '-' . $this->getPageSlug();

            // Store values, if submitted
            if (isset($_POST[$this->getSubmitButtonName()])) {
                $this->storeValues();
            }

            // The function that renders the settings page
            $renderFunction = function () {
                // For use in the template
                $settingsPage = $this;
                require dirname(__FILE__, 2) . '/includes/html/settings-page-template.php';
            };

            // Add the settings page
            if ($this->getParentSlug() !== null) {
                // As a subpage, if a parent slug is given
                add_submenu_page(
                    $this->getParentSlug(),
                    $this->getPageTitle(),
                    $this->getPageTitle(),
                    $this->getCapability(),
                    $pageSlug,
                    $renderFunction,
                    $this->getMenuPosition()
                );
            } else {
                // Or as a separate page
                add_menu_page(
                    $this->getPageTitle(),
                    $this->getPageTitle(),
                    $this->getCapability(),
                    $pageSlug,
                    $renderFunction,
                    $this->getIcon(),
                    $this->getMenuPosition()
                );
            }
        }, 100);

        return $this;
    }
}
