<?php

namespace Wordclass;

class Admin {
    /**
     * Show the admin bar, when viewing the website
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
            $newUrl = null;

            // Only check if the user has roles
            if(isset($user->roles)  &&  is_array($user->roles)) {
                // Loop over all the given role:url pairs, and use only the first occurrence
                // Because a user can have multiple (matching) roles
                if(is_array($roleUrls)) {
                    foreach($roleUrls as $role => $url) {
                        if(in_array($role, $user->roles)) {
                            $newUrl = $url;
                            break;
                        }
                    }
                }

                // If 1 role:url pair is given, see if it matches
                else if(is_string($roleUrls)  &&  in_array($roleUrls, $user->roles))
                    $newUrl = $url;

                // If the above didn't have any matches, look for a wildcard
                if($newUrl == null) {
                    if(is_array($roleUrls)  &&  array_key_exists('*', $roleUrls))
                        $newUrl = $roleUrls['*'];
                    else if(is_string($roleUrls)  &&  $roleUrls == '*')
                        $newUrl = $url;
                }

                // Return a new URL, if it's set
                if($newUrl != null) {
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
