# Wordclass\Traits\CanSetTextDomain
Set or get the text domain in a class, for translation in Wordpress.  
This is optional, and can also be set globally with Init::defaultTextDomain().  
This method does overwrite the value set with Init::defaultTextDomain().

#### Example
```php
// Setting
ClassUsingTrait::textDomain('your-text-domain');

// Getting
$description = __('Original untranslated string', static::textDomain());
```
