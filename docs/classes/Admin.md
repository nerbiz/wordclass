# Wordclass\Admin

### ::showBar()
Show or hide the admin bar at the top, when viewing the website.

#### Example
```php
// Always show
Wordclass\Admin::showBar(true);
// Never show
Wordclass\Admin::showBar(false);
// Only show when logged in (same result as not calling this method)
Wordclass\Admin::showBar();
```

### ::roleRedirects()
Redirect to a custom URL after login, specified per role.  
It is possible to use a wildcard character ('*'), to redirect all roles, though explicitly named roles take precedence.  
Unspecified roles will just redirect to the default Wordpress admin.

#### Example
```php
// As an array
Wordclass\Admin::roleRedirects([
    'administrator' => 'some/custom/url',
    'editor'        => 'another/url',
    // Wildcard also acts as a fallback, when the above roles don't match
    '*'             => 'url/for/all/roles'
]);

// As strings, in case of 1 role/URL
Wordclass\Admin::roleRedirects('administrator', 'some/custom/url');
Wordclass\Admin::roleRedirects('*', 'url/for/all/roles');
```

### ::footerText()
Edit the admin footer text (HTML is supported).  
The first argument is the text to show, the second argument specifies the location (see the example).  
This method can be repeated, so it is possible to first prepend/replace some text, and then append some more text, for instance.

#### Example
```php
// Insert text before the current text
Wordclass\Admin::footerText('<em>Before current.</em> ', 'before');

// Insert text after the current text
Wordclass\Admin::footerText(' <em>After current.</em>', 'after');

// Replace the current text
Wordclass\Admin::footerText('<em>Replacing current.</em>');
```
