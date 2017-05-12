# Wordclass\Plugins
Depends on [tgmpa/tgm-plugin-activation](https://github.com/TGMPA/TGM-Plugin-Activation).  
See the [documentation](http://tgmpluginactivation.com/configuration/) for more information.  
Set required/recommended plugins.

### ::config()
Overwrite the default config. This is optional, but if set, it needs to be done before including plugins.

### ::include()
Require or recommend 1 or more pugins, in slug:options pairs.  
To add only 1, it is possible to provide a string (slug) and the options as a second parameter.

#### Example
```php
// 1 config value overwritten
Wordclass\Plugins::config([
    'dismissable' => false
]);

// One plugin
Wordclass\Plugins::include('all-in-one-wp-migration', [
    'name'     => 'All-in-One WP Migration',
    'required' => false
]);

// Another one can be added later
Wordclass\Plugins::include('disable-feeds', [
    'name'     => 'Disable Feeds',
    'required' => false
]);

// Multiple plugins, at the same time (same result as the above)
Wordclass\Plugins::include([
    'all-in-one-wp-migration' => [
        'name'     => 'All-in-One WP Migration',
        'required' => false
    ],
    'disable-feeds' => [
        'name'     => 'Disable Feeds',
        'required' => false
    ]
]);
```
