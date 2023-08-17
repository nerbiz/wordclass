<?php

namespace YourNamespace;

use Nerbiz\WordClass\InputFields\CheckboxInputField;
use Nerbiz\WordClass\InputFields\EditorInputField;
use Nerbiz\WordClass\InputFields\ExplanationText;
use Nerbiz\WordClass\InputFields\GeneralInputField;
use Nerbiz\WordClass\InputFields\MediaInputField;
use Nerbiz\WordClass\InputFields\PostInputField;
use Nerbiz\WordClass\InputFields\RadioButtonsInputField;
use Nerbiz\WordClass\InputFields\SelectInputField;
use Nerbiz\WordClass\InputFields\TextareaInputField;
use Nerbiz\WordClass\SettingsPage;
use Nerbiz\WordClass\SettingsPageSection;

class YourSettingsPage
{
    /**
     * @var SettingsPage
     */
    protected SettingsPage $settingsPage;

    /**
     * Create the settings page
     * @return void
     */
    public function create(): void
    {
        $this->settingsPage = (new SettingsPage('Website settings'))
            // Make it a submenu of the normal WordPress settings
            ->setParentSlug('options-general.php')
            // Allow editors to use this settings page
            // (And users with more permissions)
            ->setCapabilityByRole('editor');

        $this->addContactSection();
        $this->addTestSection();

        // Don't forget this
        $this->settingsPage->create();
    }

    /**
     * @return void
     */
    protected function addContactSection(): void
    {
        // Each section needs a unique ID, title and input fields
        $section = new SettingsPageSection('contact', 'Contact', [
            // It's possible to add subheadings with extra descriptive text
            (new ExplanationText('Contact information settings'))
                ->setDescription('Several values to be shown on the contact page'),
            // Some general input fields with type="text"
            new GeneralInputField('street', 'Street'),
            new GeneralInputField('house_number', 'House number'),
            new GeneralInputField('postcode', 'Postcode'),
            new GeneralInputField('city', 'City'),
            (new GeneralInputField('phone', 'Phone number'))
                // Some useful hint for the input
                ->setDescription('Format: 123-456-7890')
                // Any attribute can be set
                ->setAttributes([
                    'type' => 'tel',
                    'pattern' => '[0-9]{3}-[0-9]{3}-[0-9]{4}',
                ]),
            (new GeneralInputField('email', 'Email address'))
                ->setAttributes(['type' => 'email']),
        ]);

        $this->settingsPage->addSection($section);
    }

    /**
     * This section features all possible input fields for a complete example
     * @return void
     */
    protected function addTestSection(): void
    {
        /**
         * This is a more elaborate example,
         * showing all possible input elements
         */

        $section = new SettingsPageSection('general', 'General', [
            (new GeneralInputField('company_name', 'Company name'))
                // All input fields support an optional description
                ->setDescription('The name of the company'),
            (new GeneralInputField('max_news_items', 'Maximum amount of news items'))
                ->setDescription('Referring to the items on the homepage')
                ->setAttributes([
                    'type' => 'number',
                    'min' => 3,
                    'max' => 9,
                    // Boolean attributes only need the name
                    'required',
                    // data-* attributes are possible too
                    'data-test-value' => 'something',
                ]),
            // A textarea field for more content than a normal <input>
            (new TextareaInputField('vacation_message', 'Vacation message'))
                ->setDescription('A short message to show during the vacation period'),
            // A text editor for rich text
            (new EditorInputField('footer_content', 'Footer content'))
                ->setDescription('Company description on each page in the footer'),
            // An input field using the WordPress media library
            new MediaInputField('logo_id', 'Logo'),
            // A subheading can be placed anywhere in between
            new ExplanationText('Some settings with predefined options'),
            // Checkboxes and radio buttons for simple or predefined settings
            new CheckboxInputField('show_map', 'Show a map on the contact page'),
            // Checkbox values are stored as 1/0 by default, but can be changed
            new CheckboxInputField('sort_items', 'Sort items in ascending order?', 'asc', 'desc'),
            // Radio button fields expect an array of value=>label pairs
            new RadioButtonsInputField('map_type', 'The type of map to show', [
                'roadmap' => 'Default map with roads',
                'satellite' => 'A map with images from satellites',
                'terrain' => 'A map featuring terrain information',
            ]),
            // A dropdown element, which expects an array of value=>label pairs
            new SelectInputField('country', 'Country', [
                'country_1' => 'Country 1',
                'country_2' => 'Country 2',
                'country_3' => 'Country 3',
            ]),
            // A posts dropdown, to choose an ID of a specific post
            // PostType objects are also supported
            (new PostInputField('promotion_page', 'Promotion page', ['post', 'page']))
                ->setDescription('Where a promo button could link to'),
        ]);

        $this->settingsPage->addSection($section);
    }
}
