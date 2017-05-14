<?php

namespace Wordclass;

use Wordclass\Utilities;

class PageBuilder {
    use Traits\CanSetTextDomain;



    /**
     * The prefix to use for the names of added row style options
     * Format is prefix-name (hyphen is automatically added)
     * @var String
     */
    private static $_fieldPrefix = 'custom';

    /**
     * The name of the group to which the custom widgets will be added
     * @var String
     */
    private static $_widgetGroup = 'custom-widgets';



    /**
     * Set the prefix to use for names of added row style options
     * @param String  $prefix
     */
    public static function setFieldPrefix($prefix) {
        static::$_fieldPrefix = $prefix;
    }



    /**
     * Set the name of the group the custom widgets will be added to
     * @param String  $name
     */
    public static function setWidgetsGroup($name) {
        static::$_widgetGroup = $name;
    }



    /**
     * Add an option to the row styles
     * @param Array  $args  The arguments for the new option
     *                        label: The label of the field
     *                        slug: (Optional) the field input name (default: label transformed into slug)
     *                        type: The type of input: checkbox | text | code | measurement | color | image | select
     *                        group: The group to show the option under: attributes | layout | design
     *                        description: (Optional) the description under the field (default: null)
     *                        priority: (Optional) Where to show the option (default: 11)
     */
    public static function addRowStyleOption($args) {
        // Default values
        $args['slug'] = @$args['slug']  ?:  Utilities::createSlug($args['label']);
        $args['priority'] = @$args['priority']  ?:  11;

        // Prevent translating 'null', if no description is given
        if(isset($args['description']))
            $args['description'] = __($args['description'], static::textDomain());
        else
            $args['description'] = null;

        add_filter('siteorigin_panels_row_style_fields', function($fields) use ($args) {
            // Create the field name, using the prefix
            $fieldName = static::$_fieldPrefix . '-' . $args['slug'];

            $fields[$fieldName] = [
                'name'        => __($args['label'], static::textDomain()),
                'type'        => $args['type'],
                'group'       => $args['group'],
                'description' => $args['description'],
                'priority'    => $args['priority'],
            ];

            return $fields;
        });
    }



    /**
     * Add CSS classes to either div.panel-grid, or div.panel-row-style elements
     * @param  String        $element  grid / row
     * @param  String|Array  $classes  1 class: provide a string
     *                                 multiple: provide a space-separated string, or an array of strings
     */
    public static function addRowClasses($element, $classes) {
        $classes = (array) $classes;

        // Create an array of classes to add
        // When a class has a space, transform it into an array
        $addClasses = [];
        foreach($classes as $class)
            $addClasses = array_merge($addClasses, explode(' ', $class));

        // Add to all div.panel-grid elements
        if($element == 'grid') {
            add_filter('siteorigin_panels_row_classes', function($current) use($addClasses) {
                return array_merge($current, $addClasses);
            });
        }

        // Add to all div.panel-row-style elements
        else if($element == 'row') {
            add_filter('siteorigin_panels_row_style_attributes', function($attributes, $args=null) use ($addClasses) {
                // Append the classes to possibly existing classes
                $attributes['class'] = array_merge($attributes['class'], $addClasses);

                return $attributes;
            });
        }
    }



    /**
     * Set a custom style attribute on all div.panel-row-style elements
     * @param  String|Array  $property  string: The CSS property
     *                                  array: property:value pairs
     * @param  String|null   $value     In case $property is a string, this is the value for it
     */
    public static function addRowStyles($property, $value=null) {
        if(is_string($property))
            $style = $property . ': ' . $value . ';';

        else if(is_array($property)) {
            $style = '';
            foreach($property as $key => $value)
                $style .= $key . ': ' . $value . '; ';
        };

        add_filter('siteorigin_panels_row_style_attributes', function($attributes, $args=null) use ($style) {
            // Append the style to a possibly existing style
            $attributes['style'] = trim($attributes['style'] . ' ' . $style);

            return $attributes;
        });
    }



    /**
     * Add a theme widgets directory
     * @param  String  $directory
     */
    public static function addWidgetsDirectory($directory) {
        // Make sure there is 1 trailing slash
        $directory = rtrim($directory, '/') . '/';

        add_filter('siteorigin_widgets_widget_folders', function($directories) use ($directory) {
            $directories[] = $directory;
            return $directories;
        });
    }



    /**
     * Add a custom widgets tab
     * @param  String  $label  The label of the tab
     */
    public static function addWidgetsTab($label) {
        add_filter('siteorigin_panels_widget_dialog_tabs', function($tabs) use ($label) {
            $tabs[] = [
                'title'  => __($label, static::textDomain()),
                'filter' => [
                    'groups' => [static::$_widgetGroup]
                ]
            ];

            return $tabs;
        });
    }



    /**
     * Add non-custom widgets to the custom group / tab
     * @param Array|String  $classes
     */
    public static function addWidgetsToGroup($classes) {
        $classes = (array) $classes;

        add_filter('siteorigin_panels_widgets', function($widgets) use ($classes) {
            foreach($classes as $class) {
                if(class_exists($class)  &&  isset($widgets[$class])) {
                    $widgets[$class]['groups'] = [static::$_widgetGroup];
                    $widgets[$class]['icon'] = 'dashicons dashicons-tagcloud';
                }
            }

            return $widgets;
        });
    }
}
