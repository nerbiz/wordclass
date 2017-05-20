# Wordclass\SettingsPage
Uses the `CanSetTextDomain` trait.  
Create a custom settings page as a submenu under Admin > Settings, using a method chain.  

### ::create()
Create the settings page and start the method chain.  
The first argument is the browser window title, the menu title, and the title at the top of the settings page.  
The second argument is the name of the settings group, in which the settings will go.  
The third argument is the menu item icon (optional, default 'dashicons-admin-settings').  
All icons can be found on the [Wordpress dashicons page](https://developer.wordpress.org/resource/dashicons/). Alternatively, this supports an image URL, or SVG as base64 (a string starting with 'data:image/svg+xml;base64,'). If this is set to 'none', there will be no icon, but it's settable with CSS.  
The fourth argument is the menu position (optional, default null).

If you'd like to keep the default icon, but specify a custom menu order, then set null as the icon argument.

### ::pageSlug()
This is optional, because by default the page slug is the page title converted to a slug. Use this method to overwrite this default value.

### ::prefix()
Set the prefix for all input fields (name and ID), the settings group name and section IDs.  
Names will be appended with an underscore, and IDs with a hyphen.  
This is optional, the default value is 'xx'.

### ::addSection()
Add a section to the settings page, with fields in it.  
The first argument is the identifier of the section.  
The second argument is the title of the section.  
The third argument is the subtitle of the section.  
The fourth argument contains an array with fields definitions, in name:options pairs (where name is the 'name' attribute). The options are 'type' and 'title' (the label of the input element). The supported types are: 'text', 'checkbox' and 'wysiwyg' (more types will be added).

#### Example
```php
Wordclass\SettingsPage::setTextDomain('your-text-domain');

// A minimal example
Wordclass\SettingsPage::create('Custom settings', 'your-settings-group')
    ->addSection('contact', 'Contact Info', '', [
        'name'         => ['type' => 'text', 'title' => 'Name'],
        'phone_number' => ['type' => 'text', 'title' => 'Phone number']
    ]);

// An elaborate example
Wordclass\SettingsPage::create('Custom settings', 'your-settings-group', 'dashicons-admin-tools', 2)
    ->pageSlug('your-page-slug')
    ->prefix('abc')
    ->addSection('contact', 'Contact Info', '', [
        'name'         => ['type' => 'text', 'title' => 'Name'],
        'phone_number' => ['type' => 'text', 'title' => 'Phone number']
    ]);
    ->addSection('social', 'Social Networks', 'Subtitle or extra descriptive text', [
        'twitter'  => ['type' => 'text', 'title' => 'Twitter'],
        'facebook' => ['type' => 'text', 'title' => 'Facebook'],
        'linkedin' => ['type' => 'text', 'title' => 'LinkedIn']
    ])
    ->addSection('various', 'Various input types', '', [
        'subtitle' => ['type' => 'text', 'title' => 'Page subtitle'],
        'captcha'  => ['type' => 'checkbox', 'title' => 'Use captcha?'],
        'footer'   => ['type' => 'wysiwyg', 'title' => 'Footer content']
    ]);

// Get the value of an option
echo get_option('xx_phone_number');
echo get_option('abc_phone_number');
echo get_option('abc_twitter');
```
