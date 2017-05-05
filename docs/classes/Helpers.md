# Wordclass\Helpers

### ::getImage(), ::getFeaturedImage(), ::getMetaImage()
Get information about an image resource, either general, featured or a custom field. The first argument is the ID of the image. The second 'size' argument accepts any registered image size, like 'thumbnail', 'medium', 'large', 'full' or a custom added size (default 'full'). The third argument specifies the type of info that will be returned (default 'url').

#### Example
```php
// Get the URL of an image
$imageUrl = Wordclass\Helpers::getImage(35, 'full', 'url');
// Get an array of image info: [url, width, height, is_intermediate]
$imageArray = Wordclass\Helpers::getImage(35, 'full', 'array');
// Get an image as an <img> tag
$imageTag = Wordclass\Helpers::getImage(35, 'full', 'html');

// Convenience wrapper for featured images
// The first argument is a post ID
$featuredImage = Wordclass\Helpers::getFeaturedImage(21, 'full', 'url');

// Convenience wrapper for custom field images
// The first argument is a post ID, the second is a custom field name
$metaImage = Wordclass\Helpers::getMetaImage(21, 'poster', 'full', 'url');
```

### ::getTaxonomySlug()
Get the slug of a taxonomy.

#### Example
```php
// This could return 'genre', as in www.example.com/genre/action
$slug = Wordclass\Helpers::getTaxonomySlug('movie_genres');
```

### ::getTaxonomyItems()
Get all the items of a given taxonomy.

#### Example
```php
// Get all the movie genres, for example
$genres = Wordclass\Helpers::getTaxonomyItems('movie_genres');
```

### ::getPostTaxonomies()
Get the taxonomies that a post has.

#### Example
```php
// Get all the movie genres of post ID 21, which would be a movie post type
$movieGenres = Wordclass\Helpers::getPostTaxonomies(21, 'movie_genres');
```
