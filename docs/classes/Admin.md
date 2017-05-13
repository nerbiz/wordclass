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
Unspecified roles will just redirect to the default Wordpress admin.

#### Example
```php
// As an array
Wordclass\Admin::roleRedirects([
    'administrator' => 'some/custom/url',
    'editor'        => 'another/url'
]);

// As strings, in case of 1 role/URL
Wordclass\Admin::roleRedirects('administrator', 'some/custom/url');
```
