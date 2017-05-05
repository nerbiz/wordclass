# Wordclass\Traits\CanPreventAssetsCaching
Creates a string like '?v=1483228800' in the static class property `$_assetAppend`, using the current Unix timestamp, for cache busting purposes.  
When set to false, this is an empty string.

#### Example
```php
ClassUsingTrait::preventCache(true);

// Inside class
$assetUrl = 'path/to/styles.css' . static::$_assetAppend;
```
