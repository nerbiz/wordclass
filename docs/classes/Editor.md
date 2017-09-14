# Wordclass\Editor

See the [documentation](https://www.tinymce.com/docs-3x/reference/buttons/) for all the button names.  

### ::forceAdvanced()
Force the 2nd row of buttons to show, this is otherwise shown with the 'Toolbar Toggle' button.  
This method takes 1 optional argument, whether to keep the 'Toolbar Toggle' button (true) or remove it (false, this is default).

#### Example
```php
// Force the 2nd (advanced) buttons row, removing the toggle button
// These calls do the same thing
Wordclass\Editor::forceAdvanced();
Wordclass\Editor::forceAdvanced(false);

// Force the 2nd (advanced) buttons row, but keeping the toggle button
Wordclass\Editor::forceAdvanced(true);
```

### ::addButton()
Add a button to the TinyMCE editor.  
The first argument sets the toolbar to add the button to, 1 is the default, 2 is the advanced toolbar.  
The third argument is optional, and specifies after which existing button it needs to be added, or type 'first' to make the button the first one. If an 'after' button is specified, but doesn't exist, the button will be added to the end.

#### Example
```php
// Add an 'underline' button at the end of the default toolbar
Wordclass\Editor::addButton(1, 'underline');

// Add an 'underline' button after the 'italic' button of the default toolbar
Wordclass\Editor::addButton(1, 'underline', 'italic');

// Add an 'underline' button as the first button of the advanced toolbar
Wordclass\Editor::addButton(2, 'underline', 'first');
```

### ::removeButton(), ::replaceButton()
Remove and/or replace a button in the TinyMCE editor.  
The first argument sets the toolbar to remove/replace the button on, 1 is the default, 2 is the advanced toolbar.  
The third argument of removeButton() is optional, and specifies with which button it needs to be replaced.  
For replaceButton(), the third argument is required, however.  
If the button to remove doesn't exist, nothing will be removed, and if the replacement name is invalid, the button will only be removed.

#### Example
```php
// Remove the 'blockquote' button of the default toolbar
Wordclass\Editor::removeButton(1, 'blockquote');

// Replace the 'blockquote' button with a 'strikethrough' button of the default toolbar
// These methods do the same, the replaceButton() method is only added for semantics
Wordclass\Editor::removeButton(1, 'blockquote', 'strikethrough');
Wordclass\Editor::replaceButton(1, 'blockquote', 'strikethrough');
```
