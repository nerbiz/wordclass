# Wordclass\PageBuilder
For use with the plugin [Page Builder by SiteOrigin](https://wordpress.org/plugins/siteorigin-panels/).
Uses the `CanSetTextDomain` trait.

### ::setFieldPrefix()
### ::addRowStyleOption()
### ::addPanelGridClasses()
### ::addPanelRowStyle()
### ::addPanelRowClass()

### ::setWidgetsGroup()
Sets the name of a widget group, set this before calling `addWidgetsTab()` or `addWidgetsToGroup()`. If this method is not called, the group name will be 'custom-widgets'.

### ::addWidgetsTab()
Adds a tab on the left side in the 'add widget' popup, in which widgets appear, that are in the given group name.

### ::addWidgetsDirectory()
The directory in which Page Builder will search for widgets. See [the documentation](https://siteorigin.com/docs/widgets-bundle/getting-started/creating-a-widget/) on how to setup the directory structure. Basically: create a subdirectory per widget in the specified directory, then use the below structure, which is assumed by Page Builder by default.
`widget-name/widget-name.php`: the widget class file
`widget-name/tpl/default.php`: the widget template

### ::addWidgetsToGroup()
Manually add widgets to the given group name. This can be useful to put 3rd party widgets in your custom tab. Can be 1 widget name (string) or multiple (array).

#### Example
```php
// Set the text domain for all following metaboxes
Wordclass\PageBuilder::setTextDomain('your-text-domain');

Wordclass\PageBuilder::setWidgetsGroup('name-of-group');
Wordclass\PageBuilder::addWidgetsDirectory(get_template_directory() . '/widgets');
// Translated with text domain
Wordclass\PageBuilder::addWidgetsTab('Your Custom Widgets');
// Add other widgets to your group
Wordclass\PageBuilder::addWidgetsToGroup(['RevSliderWidget', 'bcn_widget']);
```
