<?php

namespace Wordclass;

class Admin {
    /**
     * Hide the admin bar, when viewing the website
     * @param Boolean  $show
     */
    public static function showBar($show) {
        if( ! is_bool($show))
            $show = true;

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
                    if(is_string($roleUrls)  &&  $role == $roleUrls)
                        return esc_url($url);

                    else if(is_array($roleUrls)) {
                        if(array_key_exists($role, $roleUrls))
                            return esc_url($roleUrls[$role]);
                    }
                }
            }

            // In case of no match or some error, just continue as intented before this function was called
            return $redirecturl;
        },
        // Make sure that 3 arguments are passed
        10, 3);
    }
}
