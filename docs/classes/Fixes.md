# Wordclass\Fixes

### ::cantLocatePluginDirectory()
Should fix the error: 'Unable to locate WordPress plugin directory', when installing or removing plugins.  
This sets the filesystem method to 'direct' using add_filter(). Usually ```define('FS_METHOD', 'direct');``` needs to be set in wp-config.php, but it turned out to not be sufficient to solve the error.

#### Example
```php
Wordclass\Fixes::cantLocatePluginDirectory();
```

### ::hiddenEditorDropdowns()
Should fix the error: the dropdown menu(s) of the TinyMCE editor not showing, particularly in modal dialogs.  
This adjusts the z-index of the dropdowns, because they're usually behind the modal dialog or something else.

#### Example
```php
Wordclass\Fixes::hiddenEditorFormatSelect();
```
