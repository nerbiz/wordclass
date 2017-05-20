# Wordclass\SettingsPage
Uses the `CanSetTextDomain` trait.  
Create a custom settings page as a submenu under Admin > Settings, using a method chain.  

### ::create()
Start the method chain, providing the title of the page as the first argument, which will be the browser window title, the menu title, and the title at the top of the settings page.  
The second argument is the settings page slug, which will be converted from the title, if it's not provided.

### ::add()
This adds the settings page, which contains nothing yet at this point.  
The first argument is the name of the settings group, in which the settings will go.  
The second argument is the menu item icon (optional, default 'dashicons-admin-settings').  
All icons can be found on the [Wordpress dashicons page](https://developer.wordpress.org/resource/dashicons/). Alternatively, this supports an image URL, or SVG as base64 (a string starting with 'data:image/svg+xml;base64,'). If this is set to 'none', there will be no icon, but it's settable with CSS.
The third argument is the menu position (optional, default null).

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

Wordclass\SettingsPage::create('Custom settings')
    ->add('xx-settings', 'dashicons-admin-tools', 2)
    ->inputPrefix('xx_')
    ->inputIdPrefix('xx-')
    ->addSection('xx-contact', 'Contact Info', '', [
        'name'         => ['type' => 'text', 'title' => 'Name'],
        'phone_number' => ['type' => 'text', 'title' => 'Phone number']
    ]);
    ->addSection('xx-social', 'Social Networks', 'Subtitle or extra descriptive text', [
        'twitter'  => ['type' => 'text', 'title' => 'Twitter'],
        'facebook' => ['type' => 'text', 'title' => 'Facebook'],
        'linkedin' => ['type' => 'text', 'title' => 'LinkedIn']
    ]);
```
