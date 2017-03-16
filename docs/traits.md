# Wordclass\Traits\CanPreventAssetsCaching

Creates a string to append to (asset) URLs, in order to prevent browsers from caching it.
It has 1 property:
```php
private static $_assetAppend
```
which is an empty string by default, and can be switched on/off like this:
```php
Class::preventCache(true);
Class::preventCache(false);
```
It creates a string like '?v=1483228800', using the current Unix timestamp.
Example implementation:
```php
$assetUrl = 'path/to/styles.css' . static::$_assetAppend;
```

# Wordclass\Traits\CanSetTextDomain

Enables a class to set a text domain for translation in Wordpress.
It has 1 property:
```php
private static $_textDomain
```
which defaults to 'text_domain', and can be changed statically, or as an instance method:
```php
Class::setTextDomain('domain');
Object->textDomain('domain');
```
Example implementation:
```php
$description = __('Original untranslated string', static::$_textDomain);
```
