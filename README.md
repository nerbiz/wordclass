# Wordclass
Work with Wordpress functionality using classes as a facade for the built-in functions.

This is a personal project, but it seemed useful enough to place it in the public domain. I created it, because I got confused with all the Wordpress functions, that either look too similar, have an unreasonable amount of parameters, or have unrememberable or ambiguous long names. The same goes for hooks. Furthermore, functions.php files tend to get long and messy sometimes, and my [OCD](https://en.wikipedia.org/wiki/Obsessive%E2%80%93compulsive_disorder) is tripping balls when that happens.

So I converted these:
```php
wp_get_attachment_image_url(get_post_meta($post->ID, 'image', true), 'large')
wp_get_attachment_image_src(get_post_meta($post->ID, 'image', true), 'large')
wp_get_attachment_image(get_post_meta($post->ID, 'image', true), 'large')
```

Into these:
```php
Wordclass\Helpers::getMetaImage($post->ID, 'image', 'url', 'large')
Wordclass\Helpers::getMetaImage($post->ID, 'image', 'array', 'large')
Wordclass\Helpers::getMetaImage($post->ID, 'image', 'html', 'large')
```

And these, which are called 'scripts' for both CSS and JS:
```php
add_action('wp_enqueue_scripts',  ...  wp_enqueue_style()  /  wp_enqueue_script()
add_action('admin_enqueue_scripts',  ...  wp_enqueue_style()  /  wp_enqueue_script()
add_action('login_enqueue_scripts',  ...  wp_enqueue_style()  /  wp_enqueue_script()
```

Into these:
```php
Wordclass\Assets::add('css', 'theme', 'assets/css/theme.css');
Wordclass\Assets::add('js', 'theme', 'assets/js/theme.js');
Wordclass\Assets::addAdmin('css', 'admin-custom', 'assets/css/admin.css');
Wordclass\Assets::addAdmin('js', 'admin-custom', 'assets/js/admin.js');
Wordclass\Assets::addLogin('css', 'login-custom', 'assets/css/login.css');
Wordclass\Assets::addLogin('js', 'login-custom', 'assets/js/login.js');
```

And here's how you make a new post type with its own taxonomy:
```php
$cptMovie = Wordclass\PostType::create('cpt_movie')
    ->textDomain('your-text-domain')
    ->name('Movies') // Plural defaults to this, but can be set with plural()
    ->singular('Movie')
    // ->arguments([...]) can be used to overwrite default arguments
    ->add();

$taxonomyGenres = Wordclass\Taxonomy::create('tax_genres')
    ->textDomain('your-text-domain')
    ->name('Genres') // Plural defaults to this, but can be set with plural()
    ->singular('Genre')
    ->forPostTypes($cptMovie) // String or array of strings (PostType objects will cast to string)
    // ->arguments([...]) can be used to overwrite default arguments
    ->add();
```

## Installation
Apart from downloading or cloning, which I don't need to explain, this project will be added to [Composer](https://getcomposer.org/), not sure when. The command will probably be:
```php
// In case you're skipping through this readme and didn't read the above line, this command won't work now
composer require nerbiz/wordclass
```
I've never added anything to Composer, so I'll have to figure that out first, and the Autoloader class included in this project might then be superfluous.

## Usage
The class files are well documented, but basically this is it:
```php
Wordclass\Class::method('u', 'wot', 'm8');
```

This 'usage' section will be updated with more detailed instructions (per class) later.

## Contributing
I'm sure you'll have great ideas about expanding this project, though I'm not sure how pull requests work (yet). Anyway, I'm open for suggestions and pull requests.

## License
This project uses the [Unlicence](http://unlicense.org/).
