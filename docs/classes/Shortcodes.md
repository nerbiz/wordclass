# Wordclass\Shortcodes
Uses the `CanSetTextDomain` trait.

### ::homeUrl()
Add the `[home_url]` shortcode, which returns the home URL of the website, with trailing slash.

#### Example
```php
Wordclass\Shortcodes::homeUrl();

// Apply
[home_url]
```

### ::copyright()
Add the `[copyright]` shortcode, which returns a copyright line like this: © 2013 - 2017 Site name.  
In case the given copyright year is the same as the current year, the it's shorter: © 2017 Site name.

#### Example
```php
Wordclass\Shortcodes::copyright();

// Apply
[copyright year='2017']
```

### ::pageLink()
Add the `[page_link]` shortcode, which creates a link to page of the website.  
The 'class' and 'target' attributes are optional.

#### Example
```php
Wordclass\Shortcodes::pageLink();

// Apply
[page_link id="1" class="css-class" target="_blank"]Link text[/page_link]
```

### ::create()
Start the shortcode creation method chain, and optionally add a corresponding button to the TinyMCE editor.  
The first argument is the tag of the shortcode.  
The second argument decides if the shortcode is enclosing (true) or self-closing (false, this is default).  
The third argument decides to add a button to the TinyMCE editor (default is true).  

### ::toolbar()
This method is optional, and specifies the toolbar to place the button on, and after which button it needs to be inserted. Default is on toolbar 1, at the end of it.

### ::buttonText()
This method is optional, and sets the name of the button in the toolbar, the default is the shortcode tag.

### ::addLabel()
This doesn't have anything to do with the shortcode itself, but does show up in the modal dialog that inserts it. It adds a line of text, useful for introducing or explaining some parameter.

### ::addParameter()
Add a parameter to the shortcode, and make it show up in the modal dialog that inserts it. See the code example below for the various options.  
The currently supported input types are 'text', 'dropdown' and 'checkbox'.

### ::hook()
The callback function that handles the shortcode. 2 arguments are passed to this function: $parameters and $content, where $content is optional, only used when the shortcode is enclosing.  
Before passing these values, the parameters are run through ```shortcode_atts()```, using the defaults specified with ```::addParameter()```. It also adds a filter named 'shortcode_atts_{$tag}', to filter the parameters with, if needed.

### ::add()
Don't forget to put this method at the end of the chain, because this actually adds everything, the preceding functions are only for setting and preparing.

#### Example
```php
Wordclass\Shortcodes::prefix('abc');

// Minimal example
// Adds the self-closing [pretty_button] shortcode with 2 text parameters,
// And a button in the default TinyMCE toolbar with 'pretty_button' text on it
Wordclass\Shortcodes::create('pretty_button')
    // Add 2 parameters, having input type 'text'
    ->addParameter('param1')
    ->addParameter('param2')
    ->hook(function($parameters) {
        // ...do something with parameters, render HTML...
    })
    ->add();

// Elaborate example
// This shortcode encloses content, and is also added to the editor toolbar
Wordclass\Shortcodes::create('pretty_button', true, true)
    // Put the button after the 'undo' button of the 2nd toolbar
    ->toolbar(2, 'redo')
    // Set a custom text on the button in the toolbar
    ->buttonText('Theme Button')
    // A line of text that explains the purpose (in the modal dialog)
    ->addLabel('Create a theme-styled button')
    ->addParameter([
        'type' => 'text',
        // This is the parameter as it is shown in the shortcode
        'name' => 'class',
        // The input field label
        'label' => 'Button class',
        // An optional tooltip
        'tooltip' => '(Optional) set a CSS class on the button'
    ])
    ->addParameter([
        'type' => 'dropdown',
        'name' => 'corner_style',
        'label' => 'Corner style',
        // The dropdown options (in value:label pairs, required for this input type)
        'values' => [
            'square' => 'Square corners',
            'rounded' => 'Rounded corners'
        ],
        // (Optional) set the default selected option
        'default' => 'rounded',
        // (Optional) prepend an empty option as the first one
        'placeholder' => '- Choose border style -'
    ])
    ->addParameter([
        'type' => 'checkbox',
        'name' => 'capitalize',
        'label' => 'Capitalize button text?',
        // (Optional) text next to the checkbox, not needed in this case
        // 'text' => 'Checkbox text',
        // (Optional) whether or not the checkbox is checked by default
        'checked' => true
    ])
    ->hook(function($parameters, $content) {
        // ...do something with parameters and enclosed content, render HTML...
    })
    ->add();
```
