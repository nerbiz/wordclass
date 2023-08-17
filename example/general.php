<?php

use Nerbiz\WordClass\Encrypter;
use Nerbiz\WordClass\Helpers;
use Nerbiz\WordClass\Options;
use Nerbiz\WordClass\Pool;
use Nerbiz\WordClass\Utilities;

/*
 * The features below are mostly used outside of functions.php
 */

// Encrypt and decrypt strings using AES-256-CBC
$encrypter = new Encrypter('62Yvf/E0WB6RU1C2P5XZptpyIdTDleb69FIAO4TB9R4=');
$encrypted = $encrypter->encrypt('Hello world!');
$decrypted = $encrypter->decrypt($encrypted);



// Apply the current prefix (in Init) to a value,
// the default separator is an underscore: xyz_some_value
Helpers::withPrefix('some_value');
// Results in xyz-some_value
Helpers::withPrefix('some_value', '-');

// Several ways to get an image based on its ID, 'large' size and 'url' type are default
// Get the image as a URL string
$imageUrl = Helpers::getImage(123);
$imageUrl = Helpers::getImage(123, 'large', 'url');
// Any registered image size name can be used
$thumbnailUrl = Helpers::getImage(123, 'thumbnail', 'url');
// Get the image as a [url, width, height, is_resized] array
$imageProperties = Helpers::getImage(123, 'large', 'array');
// Get the image as an <img> tag
$imageTag = Helpers::getImage(123, 'large', 'html');

// Getting featured images works the same as 'getImage', only then based on a post ID
$featuredImageUrl = Helpers::getFeaturedImage(456);

// Getting an image based on an option name is also possible,
// it works the same as 'getImage', only then based on an option name.
// This implicitly adds the prefix, for instance: 'xyz_logo_id',
// (it uses the Options class internally)
$logoImageUrl = Helpers::getOptionImage('logo_id');



// Several methods to work with options, implicitly adding a prefix.
// In this example, the actual option name would be 'xyz_company_name'
Options::set('company_name', 'Your Company');
$optionExists = Options::exists('company_name'); // true
$companyName = Options::get('company_name');
// A fallback value can be set, for when the option doesn't exist or is empty
$firstName = Options::get('first_name', 'John Doe');
Options::delete('company_name');
// 'Before save' and 'after get' hooks are made a little easier, using encryption as an example here
// The priority argument is optional (default 10)
Options::beforeSave('some_password', fn ($newValue) => $encrypter->encrypt($newValue), 10);
Options::afterGet('some_password', fn ($value) => $encrypter->decrypt($value), 10);



// The pool can be used as an alternative to 'global'.
// Values are set by key, and any type is supported
Pool::set('some_string', 'Lorem ipsum');
Pool::set('some_int', 53);
Pool::set('some_object', new stdClass());
Pool::set('some_array', [2, 3, 4]);
$exists = Pool::has('test'); // false
$exists = Pool::has('some_int'); // true
$someInt = Pool::get('some_int');
// A fallback value can be set, for when the value doesn't exist or is empty
$someInt = Pool::get('first_name', 'John Doe');
// This returns all the contents of the pool (as an array)
$contents = Pool::getAll();



// Convert a string to a slug (lowercase with separators, without accents)
echo Utilities::createSlug('Your Value'); // your-value
echo Utilities::createSlug('Toto je příklad'); // toto-je-priklad
// The separator can be changed
echo Utilities::createSlug('Your Value', '_'); // your_value
echo Utilities::createSlug('Toto je příklad', '_'); // toto_je_priklad
