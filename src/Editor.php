<?php

namespace Wordclass;

class Editor {
    /**
     * Get the buttons filter to use, based on the toolbar
     * @param  Integer  $toolbar  The toolbar number, 1 = default, 2 = advanced
     * @return String
     */
    private static function getButtonsFilter($toolbar) {
        // Only 1 or 2 are valid values
        $toolbar = (in_array($toolbar, [1, 2])) ? $toolbar : 1;

        // Define the filter
        $filter = 'mce_buttons' . (($toolbar == 2) ? '_2' : '');

        return $filter;
    }



    /**
     * Add a button to the TinyMCE editor
     * @param  Integer  $toolbar  The toolbar number, 1 = default, 2 = advanced
     * @param  String   $name     The name of the button
     * @param  String   $after    (Optional) the name of the button to place the new button after
     *                            'first' places the button as the first one
     */
    public static function addButton($toolbar, $name, $after=null) {
        $filter = static::getButtonsFilter($toolbar);

        add_filter($filter, function($buttons) use ($name, $after) {
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
     * @param  Integer  $toolbar      The toolbar number, 1 = default, 2 = advanced
     * @param  String   $name         The name of the button to remove
     * @param  String   $replacement  (Optional) the name of the button to replace the removed one with
     */
    public static function removeButton($toolbar, $name, $replacement=null) {
        $filter = static::getButtonsFilter($toolbar);

        add_filter($filter, function($buttons) use ($name, $replacement) {
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
     * @param  Integer  $toolbar    The toolbar number, 1 = default, 2 = advanced
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
     * Force the advanced toolbar (2nd row of buttons) of the TinyMCE editor
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
}
