# Wordclass\Traits\CanSetTextDomain
Enables a class to set a text domain for translation in Wordpress.

#### Example
```php
// Static
ClassUsingTrait::setTextDomain('domain');
// On instance
ObjectUsingTrait->textDomain('domain');

// Inside class
$description = __('Original untranslated string', static::$_textDomain);
```
