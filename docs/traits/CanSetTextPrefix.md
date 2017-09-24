# Wordclass\Traits\CanSetPrefix
Set or get the text domain in a class, for translation in Wordpress.  
The order of precedence is:  
1. Value set with ClassUsingTrait::prefix()
2. Value set with Init::defaultPrefix()
3. Default value ('wc')

#### Example
```php
// Setting
Wordclass\ClassUsingTrait::prefix('abc');

// Getting (inside class)
$inputName = static::prefix() . '_address';
```
