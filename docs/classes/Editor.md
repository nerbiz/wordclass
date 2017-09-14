# Wordclass\Editor

See the [documentation](https://www.tinymce.com/docs-3x/reference/buttons/) for all the button names.  

### ::addButton()
Add a button to the TinyMCE editor.  
The second argument is optional, and specifies after which existing button it needs to be added, or type 'first' to make the button the first one. If an 'after' button is specified, but doesn't exist, the button will be added to the end.

#### Example
```php
// Add an 'underline' button at the end
Wordclass\Editor::addButton('underline');

// Add an 'underline' button as the first button
Wordclass\Editor::addButton('underline', 'first');

// Add an 'underline' button after the 'italic' button
Wordclass\Editor::addButton('underline', 'italic');
```

### ::removeButton(), ::replaceButton()
Remove and/or replace a button in the TinyMCE editor.  
The second argument of removeButton() is optional, and specifies with which button it needs to be replaced.  
For replaceButton(), the second argument is required, however.  
If the button to remove doesn't exist, nothing will be removed, and if the replacement name is invalid, the button will only be removed.

#### Example
```php
// Remove the 'blockquote' button
Wordclass\Editor::removeButton('blockquote');

// Replace the 'blockquote' button with a 'strikethrough' button
// These methods do the same, the replaceButton() method is only added for semantics
Wordclass\Editor::removeButton('blockquote', 'strikethrough');
Wordclass\Editor::replaceButton('blockquote', 'strikethrough');
```

### ::forceAdvanced()
Force the 2nd row of buttons to show, this is otherwise shown with the 'Toolbar Toggle' button.  
This method takes 1 optional argument, whether to keep the 'Toolbar Toggle' button (true) or remove it (false, this is default).

#### Example
```php
// Force the 2nd (advanced) buttons row, removing the toggle button
Wordclass\Editor::forceAdvanced();
Wordclass\Editor::forceAdvanced(false);

// Force the 2nd (advanced) buttons row, but keeping the toggle button
Wordclass\Editor::forceAdvanced(true);
```

### ::forToolbar()
Set which toolbar to make modifications to.  
The only argument this method takes is the toolbar number, either 1 (the top, default toolbar) or 2 (the 2nd, advanced toolbar).  
By default, the default toolbar is used, and this method only makes sense if the advanced toolbar is used.  

#### Example
```php
// Add an 'underline' button to the default toolbar
Wordclass\Editor::addButton('underline');

// Then remove the 'strikethrough' button from the advanced toolbar
Wordclass\Editor::forToolbar(2);
Wordclass\Editor::removeButton('strikethrough');

// Switch back to the default toolbar
Wordclass\Editor::forToolbar(1);
// ...do things with the default toolbar
```
