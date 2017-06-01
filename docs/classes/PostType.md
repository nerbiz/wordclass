# Wordclass\PostType
Uses the `CanSetTextDomain` trait.  
Register custom post types, using a method chain. 'Custom post type' will be typed as CPT from here on for brevity.  
For `::labels()` and `::arguments()`, see the [register_post_type() documentation](https://codex.wordpress.org/Function_Reference/register_post_type#Parameters) for all possible arguments.  
When echo'd, a PostType instance returns the ID given with `create()`.

### ::create()
Start the method chain, setting the ID of the CPT as its only argument.

### ::name()
Set the name (and menu name) of the CPT.  
This also sets the description (name prepended with 'Custom Post Type: ') and slug (converted from name). However, these values can be set individually with the methods below.

### ::slug()
Set how the CPT should show in the URL.  
In the case of 'movies', it would be `example.com/movies/your-movie`.

### ::singular()
Set the singular name of the CPT.

### ::description()
Set the CPT description.

### ::taxonomies()
Set 1 (string) or more (array of strings) taxonomies that this CPT has.

### ::labels(), ::arguments()
Overwrite 1 or more default labels or arguments.  
Labels are one of the CPT arguments, other CPT arguments can be set with `::arguments()`.  
Pass an array of options to this method, the default values will be overwritten using `array_replace_recursive()`, so you only have to provide the option(s) you wish to overwrite, the rest will stay the same.

### ::add()
Registers the CPT, needs to be called at the end of the method chain.

#### Example
```php
Wordclass\PostType::setTextDomain('your-text-domain');

// The most minimal example
$cptMovies = Wordclass\PostType::create('cpt_movies')
    ->name('Movies')
    ->singular('Movie')
    ->add();

// A full example
$cptNews = Wordclass\PostType::create('cpt_news')
    ->name('News Items')
    ->slug('your-slug')
    ->singular('News Item')
    ->description('A collection of all news items')
    ->taxonomies('news_categories')
    ->labels(['add_new_item' => 'Add New News Item'])
    ->arguments([
        'menu_position' => 8,
        'has_archive'   => false,
        'rewrite'       => [
            'feeds' => false
        ]
    ])
    ->add();
```
