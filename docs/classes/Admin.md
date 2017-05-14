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
