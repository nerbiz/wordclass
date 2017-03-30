# Wordclass classes

## Wordclass\Admin

Admin-related actions.

### ::showBar()
Show or hide the admin bar at the top, when viewing the frontend.

#### Example
```php
Admin::showBar(true);
Admin::showBar(false);
```

### ::roleRedirect()
Redirect to a custom URL after login, specified per role. Each URL is filtered with esc_url(). Unspecified roles will just redirect to the default Wordpress backend.

#### Example
```php
// As an array
Admin::roleRedirect([
    'administrator' => 'some/custom/url',
    'editor'        => 'another/url'
]);

// As strings, in case of 1 role/URL
Admin::roleRedirect('administrator', 'some/custom/url');
```

---
