<?php

namespace Wordclass;

class Editor {
    /**
     * Add a button to the TinyMCE editor
     * @param String  $name  The name of the button
     * @param String  $after (Optional) the name of the button to place the new button after
     *                       'first' places the button as the first one
     */
    public static function addButton($name, $after=null) {
        add_filter('mce_buttons', function($buttons) use ($name, $after) {
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
        add_filter('mce_buttons', function($buttons) use ($name, $replacement) {
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
}
