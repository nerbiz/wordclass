<?php

namespace Wordclass;

class Editor {
    /**
     * Inidicates if the shortcode array and plugin have been created
     * @var Boolean
     */
    private static $_shortcodeButtonsPrepared = false;



    /**
     * Get the buttons filter to use, based on the toolbar
     * @param  Integer  $number  The toolbar number, 1 = default, 2/3/4 = advanced
     * @return String
     */
    private static function getButtonsFilter($number) {
        // Fallback, and the filter name for toolbar 1
        $filter = 'mce_buttons';

        // The advanced toolbars have their own filter names
        if(in_array($number, [2, 3, 4]))
            $filter .= '_' . $number;

        return $filter;
    }



    /**
     * Force the extra button rows of the TinyMCE editor to show
     * @param  Boolean  $keepButton  (Optional) keep the toggle button
     */
    public static function forceAdvanced($keepButton=false) {
        // Remove the toggle button
        if(is_bool($keepButton)  &&  ! $keepButton)
            static::removeButton('wp_adv', 1);

        // Force the 2nd buttons row
        add_filter('tiny_mce_before_init', function($args) {
            $args['wordpress_adv_hidden'] = false;
            return $args;
        });
    }



    /**
     * Add a button to the TinyMCE editor
     * @param  String   $name     The name of the button
     * @param  String   $after    (Optional) the name of the button to place the new button after
     *                            'first' places the button as the first one
     *                            null places the button at the end
     * @param  Integer  $toolbar  (Optional) the toolbar number, 1 = default, 2/3/4 = advanced
     */
    public static function addButton($name, $after=null, $toolbar=1) {
        $filter = static::getButtonsFilter($toolbar);

        add_filter($filter, function($buttons) use($name, $after) {
            // Append the button at the end, if 'after' is not specified
            if($after == null)
                $buttons[] = $name;

            // Make the button the first one
            else if($after == 'first')
                array_unshift($buttons, $name);

            // Insert the button after an existing one
            else {
                $afterButtonKey = array_search($after, $buttons);

                // Append the button at the end, if the 'after' button is not found
                if($afterButtonKey === false)
                    $buttons[] = $name;
                else
                    array_splice($buttons, ($afterButtonKey + 1), 0, $name);
            }

            return $buttons;
        });
    }



    /**
     * Remove or replace a button from the TinyMCE editor
     * @param  String   $name         The name of the button to remove
     * @param  Integer  $toolbar      (Optional) the toolbar number, 1 = default, 2/3/4 = advanced
     * @param  String   $replacement  (Optional) the name of the button to replace the removed one with
     */
    public static function removeButton($name, $toolbar=1, $replacement=null) {
        $filter = static::getButtonsFilter($toolbar);

        add_filter($filter, function($buttons) use($name, $replacement) {
            $removeButtonKey = array_search($name, $buttons);

            // Only remove/replace the button if it exists
            // Using array_splice(), because unset() doesn't reset array keys
            if($removeButtonKey !== false)
                array_splice($buttons, $removeButtonKey, 1, $replacement);

            return $buttons;
        });
    }

    /**
     * Same as removeButton(), this is just for semantics
     * The parameter order is slightly different because of the semantics
     */
    public static function replaceButton($name, $with, $toolbar=1) {
        static::removeButton($name, $toolbar, $with);
    }



    /**
     * Move a button, optionally from one toolbar to another
     * @param  String   $name       The name of the button
     * @param  String   $after      (Optional) the name of the button to place the new button after
     *                              'first' places the button as the first one
     *                              null places the button at the end
     * @param  Integer  $toolbar    (Optional) the toolbar number, 1 = default, 2/3/4 = advanced
     * @param  Integer  $toToolbar  (Optional) the toolbar to move the button to
     *                              Uses the same toolbar, if this is null
     */
    public static function moveButton($name, $after=null, $toolbar=1, $toToolbar=null) {
        // The same toolbar is used by default
        if($toToolbar == null)
            $toToolbar = $toolbar;

        static::removeButton($name, $toolbar);
        static::addButton($name, $after, $toToolbar);
    }



    /**
     * Add a TinyMCE plugin to the editor
     * @param  String   $name     The name of the plugin
     * @param  String   $after    (Optional) the name of the button to place the new button after
     *                            'first' places the button as the first one
     *                            null places the button at the end
     * @param  Integer  $toolbar  (Optional) the toolbar number, 1 = default, 2/3/4 = advanced, or false to not add
     */
    public static function addPlugin($name, $after=null, $toolbar=1) {
        $pluginPath = 'tinymce/tinymce/plugins/' . $name . '/plugin.min.js';

        // The plugin needs to exist
        if(is_readable(__DIR__ . '/../../../' . $pluginPath)) {
            // Add the plugin
            add_filter('mce_external_plugins', function($plugins) use($name, $toolbar, $after, $pluginPath) {
                $plugins[$name] = Init::vendorUri() . $pluginPath;
                return $plugins;
            });

            // Add a button if needed
            if($toolbar != false)
                static::addButton($name, $after, $toolbar);
        }
    }



    /**
     * Convert an input type to TinyMCE type name
     * @param  String  $type
     * @return String
     */
    private static function useTinyMceType($type) {
        if($type == 'text')
            return 'textbox';

        else if($type == 'dropdown')
            return 'listbox';

        return $type;
    }



    /**
     * Add a shortcode button to the editor
     * @param  Array    $shortcode  The shortcode definition
     *                                id: the identifier in the TinyMCE buttons array
     *                                tag: the tag of the shortcode
     *                                enclosing: whether this shortcode is enclosing (true) or self-closing (false)
     *                                buttontext: the text for the button to add
     *                                inputs: array of parameters, @see Shortcodes::addParameter() and Shortcodes::addLabel()
     * @param  Integer  $toolbar    (Optional) the toolbar number, 1 = default, 2/3/4 = advanced
     * @param  String   $after      (Optional) the name of the button to place the new button after
     *                                'first' places the button as the first one
     *                                null places the button at the end
     */
    public static function addShortcodeButton($shortcode, $toolbar=1, $after=null) {
        // Use the TinyMCE type names
        foreach($shortcode['inputs'] as $key => $input)
            $shortcode['inputs'][$key]['type'] = static::useTinyMceType($input['type']);

        // Initialize the shortcode buttons array, if not done yet
        if( ! static::$_shortcodeButtonsPrepared) {
            // Create a new JavaScript array
            add_action('admin_enqueue_scripts', function() {
                echo '<script>window.wordclassShortcodeButtons = [];</script>' . PHP_EOL;
            });

            // Add the shortcode buttons plugin
            add_filter('mce_external_plugins', function($plugins) {
                $plugins['wcshortcodebuttons'] = Init::vendorUri() . 'nerbiz/wordclass/includes/js/tinymce/plugins/wcshortcodebuttons/plugin.js';
                return $plugins;
            });

            static::$_shortcodeButtonsPrepared = true;
        }

        // Add the definitions as a JS object, so that the plugin can use it
        add_action('admin_enqueue_scripts', function() use($shortcode) {
            echo '<script>window.wordclassShortcodeButtons.push(' . json_encode($shortcode) . ');</script>' . PHP_EOL;
        });

        // Add the buttons to the editor
        static::addButton($shortcode['id'], $after, $toolbar);
    }
}
