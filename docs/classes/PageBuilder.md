# Wordclass\PageBuilder
For use with the plugin [Page Builder by SiteOrigin](https://wordpress.org/plugins/siteorigin-panels/).  
Uses the `CanSetTextDomain` trait.

### ::setFieldPrefix()
Set a prefix for custom row style options. If this method is not called, this will be 'custom'.  
A trailing hyphen is automatically put between the prefix and option slug, so the example below will become ```your-prefix-your-slug```.

### ::addRowStyleOption()
Add an option to the 'Edit Row' popup, on the right side under 'Row Styles'.  
This will be accessible in the `siteorigin_panels_row_style_attributes` filter, the 2nd argument, which is an array.  
It acts on the `div.panel-row-style` element of a row, which is added by Page Builder.  
Usually, `div.panel-grid-cell` is the direct child of `div.panel-grid`, but now `div.panel-row-style` comes in between.

#### Example
```php
Wordclass\PageBuilder::setFieldPrefix('your-prefix');
Wordclass\PageBuilder::addRowStyleOption([
    // The title of the option
    'label' => 'Test option',
    // Default is label converted to slug format (optional)
    'slug' => 'your-slug',
    // The input type of the option, possible values: checkbox / text / code / measurement / color / image / select
    'type' => 'checkbox',
    // The section to place the option under, possible values: attributes / layout / design
    'group' => 'attributes',
    // Shows right below the option (optional)
    'description' => 'Description of the option',
    // The placement of the option, with reference to others (optional)
    'priority' => 12
]);
```

### ::addRowClasses()
Adds 1 or more CSS classes to all rows.  
The first argument decides to add the CSS classes to `div.panel-grid` ('grid') or `div.panel-row-style` ('row') elements.

#### Example
```php
// Add 1 CSS class
Wordclass\PageBuilder::addRowClasses('grid', 'your-class');
// Add multiple, as a space-separated string
Wordclass\PageBuilder::addRowClasses('grid', 'class-1 class-2');
// Add multiple, as an array of strings
Wordclass\PageBuilder::addRowClasses('grid', ['class-1', 'class-2']);

// The same for 'row'
Wordclass\PageBuilder::addRowClasses('row', 'your-class');
```

### ::addRowStyles()
Add inline CSS styles to all rows.

#### Example
```php
// 1 property:value pair can be set as 2 strings
Wordclass\PageBuilder::addRowStyles('background-color', '#0CF');
// Multiple pairs can be set with an array
Wordclass\PageBuilder::addRowStyles([
    'font-size' => '200%',
    'text-align' => 'right'
]);
```

### ::setWidgetsGroup()
Sets the name of a widget group, set this before calling `addWidgetsTab()` or `addWidgetsToGroup()`.  
If this method is not called, this will be 'custom-widgets'.

### ::addWidgetsTab()
Adds a tab on the left side in the 'add widget' popup, in which widgets appear, that are in the given group name.

### ::addWidgetsDirectory()
The directory in which Page Builder will search for widgets.  
See [the documentation](https://siteorigin.com/docs/widgets-bundle/getting-started/creating-a-widget/) on how to setup the directory structure.  
Basically: create a subdirectory per widget in the specified directory, then use the below structure, which is assumed by Page Builder by default.  
`widget-name/widget-name.php` the widget class file  
`widget-name/tpl/default.php` the widget template

### ::addWidgetsToGroup()
Manually add widgets to the given group name.  
This can be useful to put 3rd party widgets in your custom tab.  
Can be 1 widget name (string) or multiple (array of strings).

#### Example
```php
Wordclass\PageBuilder::setTextDomain('your-text-domain');

Wordclass\PageBuilder::setWidgetsGroup('name-of-group');
Wordclass\PageBuilder::addWidgetsDirectory(get_template_directory() . '/widgets');
Wordclass\PageBuilder::addWidgetsTab('Your Custom Widgets');
// Add other widgets to your group
Wordclass\PageBuilder::addWidgetsToGroup(['RevSliderWidget', 'bcn_widget']);
```
