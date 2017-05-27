# Wordclass\Shortcodes

### ::baseUrl()
Add the `[base_url]` shortcode, which returns the base URL of the website, with trailing slash.

#### Example
```php
Wordclass\Shortcodes::baseUrl();

// Apply
[baseUrl]
```

### ::copyright()
Add the `[copyright]` shortcode, which returns a copyright line like this: © 2013 - 2017 Site name.  
In case the given copyright year is the same as the current year, the it's shorter: © 2017 Site name.

#### Example
```php
Wordclass\Shortcodes::copyright();

// Apply
[copyright year='2017']
```

### ::add()
Add a new shortcode.  
This is a wrapper for `add_shortcode()` and doesn't do anything extra.  
The only reason this wrapper exists, is to make the code consistent.

#### Example
```php
Wordclass\Shortcodes::add('new_shortcode', function($params, $content=null) {
    $params = shortcode_atts([
        'param1' => 'default-value-1',
        'param2' => 'default-value-2'
    ], $params);

    // ...
});

// Apply
[new_shortcode]
```
