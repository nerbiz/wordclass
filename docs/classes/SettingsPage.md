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

### ::pageSlug()
This is optional, because by default the page slug is the page title converted to a slug. Use this method to overwrite this default value.

### ::inputNamePrefix()
Set the prefix for all input fields ('name' attribute) on the settings page.

### ::inputIdPrefix()
Set the prefix for all input field IDs on the settings page. This is also the prefix for the section IDs.

### ::addSection()
Add a section to the settings page, with fields in it.  
The first argument is the identifier of the section.  
The second argument is the title of the section.  
The third argument is the subtitle of the section.  
The fourth argument contains an array with fields definitions, in name:options pairs (where name is the 'name' attribute). Currently, only input[text] elements are supported, and the options are 'type' ('text' only for now) and 'title' (the label of the input element).

#### Example
```php
Wordclass\SettingsPage::setTextDomain('your-text-domain');

// A minimal example
Wordclass\SettingsPage::create('Custom settings', 'xx-settings')
    ->inputPrefix('xx_')
    ->inputIdPrefix('xx-')
    ->addSection('contact', 'Contact Info', '', [
        'name'         => ['type' => 'text', 'title' => 'Name'],
        'phone_number' => ['type' => 'text', 'title' => 'Phone number']
    ]);

// An elaborate example
Wordclass\SettingsPage::create('Custom settings', 'xx-settings', 'dashicons-admin-tools', 2)
    ->pageSlug('your-page-slug')
    ->inputPrefix('xx_')
    ->inputIdPrefix('xx-')
    ->addSection('contact', 'Contact Info', '', [
        'name'         => ['type' => 'text', 'title' => 'Name'],
        'phone_number' => ['type' => 'text', 'title' => 'Phone number']
    ]);
    ->addSection('social', 'Social Networks', 'Subtitle or extra descriptive text', [
        'twitter'  => ['type' => 'text', 'title' => 'Twitter'],
        'facebook' => ['type' => 'text', 'title' => 'Facebook'],
        'linkedin' => ['type' => 'text', 'title' => 'LinkedIn']
    ]);
```
