# Wordclass\Assets
Uses the `CanPreventAssetsCaching` trait.

### ::add(), ::addAdmin(), ::addLogin()
Add CSS and/or JavaScript assets to a page.  
Assets can be added to the theme ('add'), to the Wordpress backend ('addAdmin') and the Wordpress login page ('addLogin').  
Theme asset URLs are relative to the (child-)theme directory, actual URLs are kept as-is.

#### Example
```php
// Enable cache busting
Wordclass\Assets::preventCache(true);

// Add 1 CSS asset at a time
Wordclass\Assets::add('css', 'theme', 'assets/css/theme.css');
Wordclass\Assets::add('css', 'theme', '//fonts.googleapis.com/css?family=Open+Sans');
Wordclass\Assets::add('css', 'theme', 'https://fonts.googleapis.com/css?family=Open+Sans');
// Add 1 JavaScript asset at a time
Wordclass\Assets::add('js', 'theme', 'assets/js/theme.js');

// Add multiple CSS assets at the same time
Wordclass\Assets::add('css', [
    'theme' => 'assets/css/theme.css',
    // With custom options
    'extra' => [
        // Path to the asset
        'path'  => 'assets/css/extra.css',
        // Asset dependencies, default is an empty array
        'after' => ['theme'],
        // The media which this asset is for, default is 'all'
        'media' => 'all'
    ],
]);

// Add multiple JavaScript assets at the same time
Wordclass\Assets::add('js', [
    'theme' => 'assets/js/theme.js',
    // With custom options
    'extra' => [
        // Path to the asset
        'path'  => 'assets/js/extra.js',
        // Asset dependencies, default is an empty array
        'after' => ['theme'],
        // Add this asset to the header (false) or footer (true), default is true
        'footer' => true
    ],
]);
```
