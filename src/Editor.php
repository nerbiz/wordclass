<?php

namespace Wordclass;

class Editor {
    /**
     * The toolbar to do operations on
     * @var Integer
     */
    private static $_forToolbar = 1;



    /**
     * Set the toolbar to work with
     * @param  Integer  $number  The toolbar number, 1 = normal, 2 = advanced
     */
    public static function forToolbar($number) {
        if($number == 1  ||  $number == 2)
            static::$_forToolbar = $number;
    }



    /**
     * Add a button to the TinyMCE editor
     * @param String  $name  The name of the button
     * @param String  $after (Optional) the name of the button to place the new button after
     *                       'first' places the button as the first one
     */
    public static function addButton($name, $after=null) {
        $filter = 'mce_buttons' . ((static::$_forToolbar == 2) ? '_2' : '');

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
     * @param  String  $name         The name of the button to remove
     * @param  String  $replacement  (Optional) the name of the button to replace the removed one with
     */
    public static function removeButton($name, $replacement=null) {
        $filter = 'mce_buttons' . ((static::$_forToolbar == 2) ? '_2' : '');

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
     * Same as removeButton() with a second argument
     * This is just for semantics
     */
    public static function replaceButton($name, $with) {
        static::removeButton($name, $with);
    }



    /**
     * Force the advanced toolbar (2nd row of buttons) of the TinyMCE editor
     * @param  Boolean  $keepButton  (Optional) keep the toggle button
     */
    public static function forceAdvanced($keepButton=false) {
        // Remove the toggle button
        if(is_bool($keepButton)  &&  ! $keepButton)
            static::removeButton('wp_adv');

        // Force the 2nd buttons row
        add_filter('tiny_mce_before_init', function($args) {
            $args['wordpress_adv_hidden'] = false;
            return $args;
        });
    }
}
