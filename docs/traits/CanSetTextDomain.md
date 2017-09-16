# Wordclass\Traits\CanSetTextDomain
Set or get the text domain in a class, for translation in Wordpress.  
The order of precedence is:  
1. Value set with ClassUsingTrait::textDomain()
2. Value set with Init::defaultTextDomain()
3. Default value ('text-domain')

#### Example
```php
// Setting
Wordclass\ClassUsingTrait::textDomain('your-text-domain');

// Getting
$description = __('Original untranslated string', static::textDomain());
```
