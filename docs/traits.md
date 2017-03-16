# Wordclass traits
---

## Wordclass\Traits\CanPreventAssetsCaching
Creates a string like '?v=1483228800', using the current Unix timestamp, for cache busting purposes.

#### Example
```php
ClassUsingTrait::preventCache(true);

// Inside class
$assetUrl = 'path/to/styles.css' . static::$_assetAppend;
```

---

## Wordclass\Traits\CanSetTextDomain
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
