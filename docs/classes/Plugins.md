# Wordclass\Plugins
Depends on [tgmpa/tgm-plugin-activation](https://github.com/TGMPA/TGM-Plugin-Activation).  
See [see documentation](http://tgmpluginactivation.com/configuration/) for more information.
Set required/recommended plugins, using a method chain.  

### ::init()
Start the method chain.

### ::config()
Overwrite the default config.

### ::include()
Require or recommend 1 or more pugins, in slug:options pairs.  
To add only 1, it is possible to provide a string (slug) and the options as a second parameter.

#### Example
```php
// One plugin, with default config
Wordclass\Plugins::init()
    ->include('all-in-one-wp-migration', [
        'name'      => 'All-in-One WP Migration',
        'required'  => false
    ])
    ->set();

// Multiple plugins
Wordclass\Plugins::init()
    ->config([
        'dismissable' => false
    ])
    ->include([
        'all-in-one-wp-migration' => [
            'name'      => 'All-in-One WP Migration',
            'required'  => false
        ],
        'disable-feeds' => [
            'name'      => 'Disable Feeds',
            'required'  => false
        ]
    ])
    ->set();
```
