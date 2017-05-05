# Wordclass\Metabox
Depends on [webdevstudios/cmb2](https://github.com/WebDevStudios/CMB2).
Uses the `CanSetTextDomain` trait.
Add a metabox to one or more post types, using a method chain: `create()->addField()->add()`, where `addField()` can be chained multiple times.

### ::create()
This starts the method chain.
It sets the ID and title of the metabox, as well as the post types to apply this metabox to.
The third (post types) argument can be a string (1 post type) or an array (multiple post types). Wordclass\PostType instances are also accepted.
The fourth argument is optional and specifies metabox options ([see here](https://cmb2.io/api/source-class-CMB2.html#47-71)). The 'id', 'title' and 'object_types' options are set with the first 3 arguments, this array sets any other option(s).

### ::addField()
Please refer to [the documentation](https://cmb2.io/docs/field-types) for all the available options per field type.
I'll try my best to make everything translatable, I just don't know of all the option names yet.

### ::add()
This adds the metabox.

#### Example
```php
// Set the text domain for all following metaboxes
Wordclass\Metabox::setTextDomain('your-text-domain');

Wordclass\Metabox::create('metabox-1', 'Movie properties', 'movie', [])
    ->addField([
        'id'      => 'poster',
        'type'    => 'file',
        // Translated with text domain
        'name'    => 'Movie poster',
        // The options below are different per field type
        'options' => [
            'url' => false
        ],
        'text'    => [
            // Translated with text domain
            'add_upload_file_text' => 'Select image'
        ]
    ])
    // Another field can be added with chaining
    ->addField([
        'id'   => 'plot',
        'type' => 'wysiwyg',
        // Translated with text domain
        'name' => 'Movie plot'
    ])
    ->add();
```
