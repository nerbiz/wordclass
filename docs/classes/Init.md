# Wordclass\Init

### ::autoloader()
Initialize the Wordclass autoloader.  
**Please note:** this autoloader is only needed when for some reason you need to manually activate autoloading.

#### Example
```php
require 'absolute/path/to/src/Init.php';
Wordclass\Init::autoloader();
```

### ::constants()
Define some useful constants, that make things shorter and/or less ambiguous.

#### Example
```php
Wordclass\Init::constants();

// Absolute paths to the template/stylesheet directory (1 trailing slash is ensured)
echo TEMPLATE_PATH;
echo STYLESHEET_PATH;

// URI paths to the template/stylesheet directory (1 trailing slash is ensured)
echo TEMPLATE_URI;
echo STYLESHEET_URI;
```

### ::defaultTextDomain()
Set or get the default text domain, this is null by default.

#### Example
```php
// Setting
Wordclass\Init::defaultTextDomain('your-text-domain');

// Getting
Wordclass\Init::defaultTextDomain();
```

### ::defaultPrefix()
Set or get the default prefix, this is null by default.

#### Example
```php
// Setting
Wordclass\Init::defaultPrefix('abc');

// Getting
Wordclass\Init::defaultPrefix();
```

### ::vendorUri()
Set or get the vendor directory URI (where the Composer packages are).  
The default is a directory named 'vendor' in the current (child-)theme directory.  
This is needed for some Wordclass features to work.

#### Example
```php
// Setting (1 trailing slash is ensured)
Wordclass\Init::vendorUri(site_url('/wp-content/vendor'));

// Getting
Wordclass\Init::vendorUri();
```
