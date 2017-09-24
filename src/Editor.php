<?php

namespace Wordclass;

class Editor {
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
            static::removeButton(1, 'wp_adv');

        // Force the 2nd buttons row
        add_filter('tiny_mce_before_init', function($args) {
            $args['wordpress_adv_hidden'] = false;
            return $args;
        });
    }



    /**
     * Add a button to the TinyMCE editor
     * @param  Integer  $toolbar  The toolbar number, 1 = default, 2/3/4 = advanced
     * @param  String   $name     The name of the button
     * @param  String   $after    (Optional) the name of the button to place the new button after
     *                            'first' places the button as the first one
     *                            null places the button at the end
     */
    public static function addButton($toolbar, $name, $after=null) {
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
     * @param  Integer  $toolbar      The toolbar number, 1 = default, 2/3/4 = advanced
     * @param  String   $name         The name of the button to remove
     * @param  String   $replacement  (Optional) the name of the button to replace the removed one with
     */
    public static function removeButton($toolbar, $name, $replacement=null) {
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
     * Same as removeButton() with a third argument
     * This is just for semantics
     */
    public static function replaceButton($toolbar, $name, $with) {
        static::removeButton($toolbar, $name, $with);
    }



    /**
     * Move a button, optionally from one toolbar to another
     * @param  Integer  $toolbar    The toolbar number, 1 = default, 2/3/4 = advanced
     * @param  String   $name       The name of the button
     * @param  String   $after      (Optional) the name of the button to place the new button after
     *                              'first' places the button as the first one
     *                              null places the button at the end
     * @param  Integer  $toToolbar  (Optional) the toolbar to move the button to
     *                              Uses the same toolbar, if this is null
     */
    public static function moveButton($toolbar, $name, $after=null, $toToolbar=null) {
        // The same toolbar is used by default
        if($toToolbar == null)
            $toToolbar = $toolbar;

        static::removeButton($toolbar, $name);
        static::addButton($toToolbar, $name, $after);
    }



    /**
     * Add a TinyMCE plugin to the editor
     * @param  String   $name     The name of the plugin
     * @param  Integer  $toolbar  (Optional) the toolbar number, 1 = default, 2/3/4 = advanced, or false to not add
     * @param  String   $after    (Optional) the name of the button to place the new button after
     *                            'first' places the button as the first one
     *                            null places the button at the end
     */
    public static function addPlugin($name, $toolbar=1, $after=null) {
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
                static::addButton($toolbar, $name, $after);
        }
    }



    public static function addShortcodeButtons($shortcodes) {
        // If 1 definition is given, put it in a surrounding array
        if(array_key_exists('text', $shortcodes))
            $shortcodes = [$shortcodes];

        // Add the definitions as a JS object, so that the plugin can use it
        add_action('admin_enqueue_scripts', function() use($shortcodes) {
            echo '<script>window.wordclassShortcodeButtons = ' . json_encode($shortcodes) . ';</script>';
        });

        // Add the shortcode buttons plugin
        add_filter('mce_external_plugins', function($plugins) {
            $plugins['wcshortcodebuttons'] = 'http://lolcathost/wordclass/includes/js/tinymce/plugins/wcshortcodebuttons/plugin.js';
            return $plugins;
        });

        // Add the buttons to the editor
        foreach($shortcodes as $shortcode)
            static::addButton(1, $shortcode['name']);
    }
}
