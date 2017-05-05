# Wordclass\Widgets
Uses the `CanSetTextDomain` trait.

### ::enableShortcodes()
Enable the working of shortcodes in widgets (doesn't work by default).

#### Example
```php
Wordclass\Widgets::enableShortcodes();
```

### ::addSidebar()
Add a sidebar.  
See the [register_sidebar() documentation](https://codex.wordpress.org/Function_Reference/register_sidebar) for more info and default values.

#### Example
```php
Wordclass\Widgets::setTextDomain(TEXT_DOMAIN);

// Most basic example
Wordclass\Widgets::addSidebar('primary-sidebar', 'Primary sidebar');

// Full example
Wordclass\Widgets::addSidebar(
    // ID
    'primary-sidebar',
    // Name / title
    'Primary sidebar',
    // Description
    'This is the primary sidebar',
    // Before/after any widget inside the sidebar
    [
        'before' => '<div class="widget">'
        'after'  => '</div>'
    ],
    // Before/after the widget title element
    [
        'before' => '<h3 class="widget-title">'
        'after'  => '</h3>'
    ]
);
```

### ::addSidebars()
Add multiple sidebars.  
Call this method with an array of ID:option pairs. Options can be a string, then it will use default options.  
For all possible options, see `addSidebar()`. Only the ID should not be in the options array.

#### Example
```php
Wordclass\Widgets::setTextDomain(TEXT_DOMAIN);

// Most basic example
Wordclass\Widgets::addSidebars([
    'primary-sidebar'   => 'Primary sidebar',
    'secondary-sidebar' => 'Secondary sidebar'
]);

// Full example
Wordclass\Widgets::addSidebars([
    'primary-sidebar'   => [
        // addSidebar() options, except ID
    ],
    'secondary-sidebar' => [
        // addSidebar() options, except ID
    ]
]);
```
