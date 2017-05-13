<?php

namespace Wordclass;

class Admin {
    /**
     * Hide the admin bar, when viewing the website
     * @param Mixed  $show  Always (true), never (false), or only when logged in (anything else)
     */
    public static function showBar($show=null) {
        if(is_bool($show))
            show_admin_bar($show);
    }



    /**
     * Redirect to a custom URL after login, based on role
     * @param  Array|String  $roleUrls  role:url pairs (or a string, the role name)
     * @param  String        $url       In case $roleUrls is a string (role), this parameter needs to be given
     */
    public static function roleRedirects($roleUrls, $url=null) {
        add_filter('login_redirect', function($redirecturl, $request, $user) use($roleUrls, $url) {
            // If role(s) are set, see if it matches the given role(s), then redirect to the corresponding URL
            if(isset($user->roles)  &&  is_array($user->roles)) {
                foreach($user->roles as $role) {
                    // Get the URL from the given role:url array
                    // When both arguments are a string, the second one is the $url variable already
                    if(is_array($roleUrls)  &&  array_key_exists($role, $roleUrls))
                        $url = $roleUrls[$role];

                    // Literal URLs
                    if(preg_match('~^https?://~', $url))
                        return esc_url($url);
                    // Other paths are relative to the home URL
                    else
                        return esc_url(home_url() . '/' . $url);
                }
            }

            // In case of no match or some error, just continue as intented before this function was called
            return $redirecturl;
        },
        // Make sure that 3 arguments are passed
        10, 3);
    }
}
