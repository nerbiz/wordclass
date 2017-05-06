# Wordclass\Utilities

### ::obscureEmailLink()
Obscures a given email address, optionally with a name.  

#### Example
```php
// Only an email address, obscures this format:
// <a href="mailto:info@example.com">info@example.com</a>
Wordclass\Utilities::obscureEmailLink('info@example.com');

// Including a name, obscures this format:
// <a href="mailto:Example Name <info@example.com>">info@example.com</a>
Wordclass\Utilities::obscureEmailLink('info@example.com', 'Example Name');
```

### ::obscurePhoneLink()
Obscures a given phone number.  
This returns a 'tel:' link, when on a phone/tablet.

#### Example
```php
Wordclass\Utilities::obscurePhoneLink('012 - 3456 789');
```

### ::createSlug()
Converts any string to slug format.

#### Example
```php
// Returns 'heavily-accentented-string-with-special-characters'
Wordclass\Utilities::createSlug('Hèávïly accêntéd string, !@ with #$% special &* characters.');
```

### ::stringToBinary()
Converts any string to binary.  
The second parameter decides to return an array (false) or a string (true).   
This is used by `::utf8ToHtmlEntities()`, which is used by the `obscure...()` methods.

#### Example
```php
// Returns '01010100011001010111001101110100'
Wordclass\Utilities::stringToBinary('Test');

/*
Returns [
    01010100,
    01100101,
    01110011,
    01110100
]
*/
Wordclass\Utilities::stringToBinary('Test', true);
```

### ::utf8ToHtmlEntities()
Converts all characters in a string to HTML entities, like &amp;#8364; for example (Euro sign).  
The second parameter decides to return the string as literal (true) or not (false).  
When returned as literal, '&' will be replaced with '&amp;amp;', so that the codes show literally, as opposed to being parsed as characters.

#### Example
```php
// Returns '€ 100' (but outputted as HTML entities in the source)
Wordclass\Utilities::utf8ToHtmlEntities('€ 100');

// Returns '&amp;#8364;&amp;#32;&amp;#49;&amp;#48;&amp;#48;'
Wordclass\Utilities::utf8ToHtmlEntities('€ 100', true);
```