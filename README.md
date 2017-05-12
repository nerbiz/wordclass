# Wordclass
Work with Wordpress functionality using classes as a facade for the built-in functions.

This is a personal project, but it seemed useful enough to place it in the public domain. I created it, because I got confused with all the Wordpress functions, that either look too similar, have an unreasonable amount of parameters, or have unrememberable or ambiguous long names. The same goes for hooks. Furthermore, functions.php files tend to get long and messy sometimes, and my [OCD](https://en.wikipedia.org/wiki/Obsessive%E2%80%93compulsive_disorder) is tripping balls when that happens.

## Installation
Apart from downloading or cloning this repo, you can include this project with [Composer](https://getcomposer.org/):
```
composer require nerbiz/wordclass
```

## Usage
The class files are well documented, but basically this is it:
```php
Wordclass\Class::method('u', 'wot', 'm8');
```
You can find more detailed documentation in the 'docs' directory.

## Contributing
I'm sure you'll have great ideas about expanding this project, though I'm not sure how pull requests work (yet). Anyway, I'm open for suggestions and pull requests.

## License
This project uses the [Unlicense](http://unlicense.org/).
