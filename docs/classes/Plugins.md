# Wordclass\Plugins
Depends on [tgmpa/tgm-plugin-activation](https://github.com/TGMPA/TGM-Plugin-Activation).  
See the [documentation](http://tgmpluginactivation.com/configuration/) for more information.  
Set required/recommended plugins.

### ::config()
Overwrite the default config. This is optional, but if set, it needs to be done before including plugins.

### ::include()
Require or recommend 1 or more pugins, in name:options pairs.  
To add only 1, it is possible to provide a string (plugin name) and the options as a second parameter.  
Or even shorter, only a string or array of strings can be given (the plugin name(s)).  
If no slug is given in the options, the name will be used, to convert it to slug format.  
An empty options array can also be given, to just use default values.

#### Example
```php
// 1 config value overwritten
Wordclass\Plugins::config([
    'dismissable' => false
]);

/** Method 1: shortest possible **/
// One plugin at a time
Wordclass\Plugins::include('All-in-One WP Migration');
Wordclass\Plugins::include('Disable Feeds');

// Multiple plugins at the same time (same result as the above)
Wordclass\Plugins::include(['All-in-One WP Migration', 'Disable Feeds']);

/** Method 2: with options specified **/
// One plugin at a time
Wordclass\Plugins::include('All-in-One WP Migration', [
    'slug'     => 'custom-slug-if-needed',
    'required' => true
]);

Wordclass\Plugins::include('Disable Feeds', [
    'required' => true
]);

// Multiple plugins at the same time (same result as the above)
Wordclass\Plugins::include([
    'All-in-One WP Migration' => [
        'slug'     => 'custom-slug-if-needed',
        'required' => true
    ],
    'Disable Feeds' => [
        'required' => true
    ]
]);
```
