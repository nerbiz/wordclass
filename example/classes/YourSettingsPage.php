<?php

namespace NewProject;

use Nerbiz\Wordclass\InputFields\MediaInputField;
use Nerbiz\Wordclass\InputFields\TextInputField;
use Nerbiz\Wordclass\SettingsPage;
use Nerbiz\Wordclass\SettingsPageSection;

class YourSettingsPage
{
    /**
     * @var SettingsPage
     */
    protected $settingsPage;

    /**
     * Create the settings page
     * @return void
     */
    public function create(): void
    {
        $this->settingsPage = (new SettingsPage())
            ->setPageTitle(__('Website settings', 'project-text-domain'))
            // Make it a submenu of the normal WordPress settings
            ->setParentSlug('options-general.php');

        $this->addContactSection();
        $this->addSocialMediaSection();

        $this->settingsPage->create();
    }

    /**
     * @return void
     */
    protected function addContactSection(): void
    {
        $section = new SettingsPageSection('contact', __('Contactgegevens', 'project-text-domain'), null, [
            new MediaInputField('logo_id', __('Logo', 'project-text-domain')),
            new TextInputField('company', __('Company name', 'project-text-domain')),
            new TextInputField('street', __('Address', 'project-text-domain')),
            new TextInputField('postcode', __('Postcode', 'project-text-domain')),
            new TextInputField('city', __('City', 'project-text-domain')),
            new TextInputField('phone', __('Phone number', 'project-text-domain')),
            new TextInputField('email', __('Email address', 'project-text-domain')),
            new TextInputField('vat_number', __('VAT number', 'project-text-domain')),
        ]);

        $this->settingsPage->addSection($section);
    }

    /**
     * @return void
     */
    protected function addSocialMediaSection(): void
    {
        $section = new SettingsPageSection('social', __('Social media', 'project-text-domain'), null, [
            new TextInputField('facebook_url', __('Facebook', 'project-text-domain')),
            new TextInputField('instagram_url', __('Instagram', 'project-text-domain')),
            new TextInputField('twitter_url', __('Twitter', 'project-text-domain')),
            new TextInputField('linkedin_url', __('LinkedIn', 'project-text-domain')),
        ]);

        $this->settingsPage->addSection($section);
    }
}
