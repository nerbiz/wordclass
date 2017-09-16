# Wordclass\Taxonomy
Uses the `CanSetTextDomain` trait.  
Register custom taxonomies, using a method chain.
When echo'd, a Taxonomy instance returns the ID given with `create()`.

### ::create()
Start the method chain, setting the ID of the taxonomy as its only argument.

### ::name()
Set the name (and menu name) of the taxonomy.  
This also sets the description (name prepended with 'Custom Taxonomy: ') and slug (converted from name). However, these values can be set individually with the methods below.

### ::slug()
Set how the taxonomy should show in the URL.  
In the case of 'movies', it would be `example.com/movies/your-movie`.

### ::singular()
Set the singular name of the taxonomy.

### ::description()
Set the taxonomy description.

### ::forPostType()
Set the post type(s) that this is a taxonomy of.  
This accepts a string for 1 post type, or an array of string for multiple.  
A PostType instance, or an array of them, or a mix of string and PostType instances, are also accepted.

### ::labels(), ::arguments()
Overwrite 1 or more default labels or arguments.  
Labels are one of the taxonomy arguments, other taxonomy arguments can be set with `::arguments()`.  
Pass an array of options to this method, the default values will be overwritten using `array_replace_recursive()`, so you only have to provide the option(s) you wish to overwrite, the rest will stay the same.

#### Example
```php
Wordclass\Taxonomy::setTextDomain('your-text-domain');

// The most minimal example
$taxGenres = Wordclass\Taxonomy::create('tax_genres')
    ->name('Genres')
    ->singular('Genre')
    ->forPostType($cptMovies)
    ->add();

// A full example
$taxNewsCategories = Wordclass\PostType::create('tax_news_categories')
    ->name('News Categories')
    ->slug('your-slug')
    ->singular('News Category')
    ->description('A collection of all news categories')
    ->forPostType($cptNews)
    ->labels(['add_new_item' => 'Add New Category Item'])
    ->arguments([
        'hierarchical' => false,
        'public'       => false,
        'rewrite'      => [
            'slug' => 'category'
        ]
    ])
    ->add();
```
