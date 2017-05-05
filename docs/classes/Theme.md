# Wordclass\Theme
Uses the `CanSetTextDomain` trait.

### ::enableFeaturedImages()
Enable the 'featured image' metabox on page/post edit screens.  
Can be 1 post type (string) or multiple (array of strings), or on everything (no argument).

#### Example
```php
// On all pages and posts
Wordclass\Theme::enableFeaturedImages();

// On pages only
Wordclass\Theme::enableFeaturedImages('page');

// On posts only
Wordclass\Theme::enableFeaturedImages('post');

// On 1 or 2 custom post types only
Wordclass\Theme::enableFeaturedImages('cpt_movies');
Wordclass\Theme::enableFeaturedImages(['cpt_movies', 'cpt_news']);
```

### ::featuredImageSize()
Set the size of the featured images.  
The first argument is the width, the second is the height.

#### Example
```php
// Not cropped (default)
Wordclass\Theme::featuredImageSize(640, 480);

// Cropped
Wordclass\Theme::featuredImageSize(640, 480, true);
```

### ::addImageSize()
Add a new image size, and add it to the size chooser.  
The first argument is the image size identifier.
The second argument is the image size name in the size chooser (dropdown).
The other arguments are the width, height and wherther or not to crop the image (default false).

#### Example
```php
Wordclass\Theme::setTextDomain('your-text-domain');

// Not cropped (default)
Wordclass\Theme::addImageSize('movie-poster-thumb', 'Movie poster thumbnail', 150, 300);

// Cropped
Wordclass\Theme::addImageSize('movie-poster-thumb', 'Movie poster thumbnail', 150, 300, true);
```

### ::addMenus()
Add menu positions, in location:description pairs.

#### Example
```php
Wordclass\Theme::setTextDomain('your-text-domain');

// 1 menu
Wordclass\Theme::addMenus('primary', 'Main menu');

// Multiple menus
Wordclass\Theme::addMenus([
    'primary'   => 'Main menu',
    'secondary' => 'Submenu'
]);
```

### ::autoWindowTitle()
Let Wordpress handle the window title. When using this, remove the &lt;title&gt; tag from &lt;head&gt;.  
For more information on the origins, see [this article](https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/).  
Apparently, this is now required for all themes, see [this article](https://make.wordpress.org/themes/2015/08/25/title-tag-support-now-required/).

#### Example
```php
Wordclass\Theme::autoWindowTitle();
```
