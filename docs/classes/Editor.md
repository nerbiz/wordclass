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
The first argument sets the toolbar to add the button to, 1 is the default toolbar, 2 is the advanced toolbar.  
The second argument is the name of the button to add.  
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
The first argument sets the toolbar to remove/replace the button on, 1 is the default toolbar, 2 is the advanced toolbar.  
The second argument is the name of the button to remove or replace.  
The third argument of removeButton() is optional, and specifies with which button it needs to be replaced. For replaceButton(), the third argument is required, however.  
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

### ::moveButton()
Move a button in the TinyMCE editor, this is a convenience method that uses removeButton() and addButton().  
The first argument sets the toolbar to move the button from, 1 is the default toolbar, 2 is the advanced toolbar.  
The second argument is the name of the button to move.  
The third argument is optional and specifies after which existing button it needs to be placed, or type 'first' to make the button the first one. If an 'after' button is specified, but doesn't exist, the button will be added to the end. The same happens when this argument is null (or not given).  
The fourth argument is also optional, and specifies the toolbar to move the button to, 1 is the default toolbar, 2 is the advanced toolbar. If not given, it will use the same value as the first argument.

If the button to move doesn't exist, it could still be added, because this method uses removeButton() and addButton(). For instance, if the 'underline' button doesn't exist, it can't be moved, but it will still be added, because it is a valid button name.

#### Example
```php
// Move the 'strikethrough' button of the advanced toolbar to the end of it
// These calls do the same thing
Wordclass\Editor::moveButton(2, 'strikethrough');
Wordclass\Editor::moveButton(2, 'strikethrough', null, 2);

// Move the 'strikethrough' button of the advanced toolbar to after the 'redo' button of it
// These calls do the same thing
Wordclass\Editor::moveButton(2, 'strikethrough', 'redo');
Wordclass\Editor::moveButton(2, 'strikethrough', 'redo', 2);

// Move the 'strikethrough' button of the advanced toolbar to the end of the default toolbar
Wordclass\Editor::moveButton(2, 'strikethrough', null, 1);

// Move the 'strikethrough' button of the advanced toolbar to after the 'italic' button of the default toolbar
Wordclass\Editor::moveButton(2, 'strikethrough', 'italic', 1);
```
