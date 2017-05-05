# Wordclass\Admin

### ::showBar()
Show or hide the admin bar at the top, when viewing the frontend.

#### Example
```php
Wordclass\Admin::showBar(true);
Wordclass\Admin::showBar(false);
```

### ::roleRedirects()
Redirect to a custom URL after login, specified per role. Each URL is filtered with esc_url().  
Unspecified roles will just redirect to the default Wordpress backend.

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
