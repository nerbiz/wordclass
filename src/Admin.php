<?php

namespace Wordclass;

class Admin {
    /**
     * Hide the admin bar, when viewing the website
     */
    public static function hideBar() {
        show_admin_bar(false);
    }



    /**
     * Redirect to a custom URL after login, based on role
     * @param  Array  $roleUrls  role:url pairs
     */
    public static function roleRedirect($roleUrls) {
        add_filter('login_redirect', function($redirecturl, $request, $user) use($roleUrls) {
            // If role(s) are set, see if it exists in the given array, then redirect to that URL
            if(isset($user->roles)  &&  is_array($user->roles)) {
                foreach($user->roles as $role) {
                    if(array_key_exists($role, $roleUrls))
                        return esc_url($roleUrls[$role]);
                }
            }

            // In case of no match or some error, just continue as intented before this function was called
            return $redirecturl;
        },
        // Make sure that 3 arguments are passed
        10, 3);
    }
}
