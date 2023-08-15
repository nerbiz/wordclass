# WordClass

Making programming with WordPress easier and more fun.

I created this, because I got confused with a number of WordPress functions, that either look too similar, have an unreasonable amount of parameters, or are unnecessarily difficult to work with. And I didn't want to always search for the names of hooks.

## Installation

WordClass requires PHP 8 and can be installed with [Composer](https://getcomposer.org/):
```
composer require nerbiz/wordclass
```

## Wiki

### Please note:

The [Wiki pages](https://github.com/nerbiz/wordclass/wiki) are outdated (version 3). They will be updated, or maybe the examples are sufficient. Either way, they're not entirely accurate anymore.

## Usage

All features are shown and documented in the [example files](https://github.com/nerbiz/wordclass/tree/master/example) directory.

It's always a good practice to use a prefix in your code, to avoid naming conflicts with WordPress, themes or plugins. WordClass therefore uses a prefix for many things, like options or post type names, with `xyz` as the default.

Please set your prefix first, because changing it later requires you to rename values in the database as well.

```php
use Nerbiz\WordClass\Init;
use Nerbiz\WordClass\Options;

// Set your prefix ('xyz' is default)
Init::setPrefix('abc');

// Example usage, this option will be stored as 'abc_option_name'
Options::set('option_name', 'option_value');
```

## Problems

If you encounter any problem, please add an [issue](https://github.com/nerbiz/wordclass/issues).

## Contributing

If you want to translate, or have some ideas about expanding or improving this project, I'm open for suggestions and pull requests.
