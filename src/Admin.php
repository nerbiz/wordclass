<?php

namespace Nerbiz\Wordclass;

class Admin implements WordclassInterface
{
    /**
     * Redirect to a custom URL after login, based on role
     * @param  array $roleUrls role:url pairs
     * @return self
     */
    public function roleRedirects(array $roleUrls): self
    {
        add_filter('login_redirect', function ($redirectUrl, $request, $user) use ($roleUrls) {
            // Check if the user has roles
            if (isset($user->roles) && is_array($user->roles)) {
                // Loop over all the given role:url pairs, and use the first match
                foreach ($roleUrls as $role => $url) {
                    if (in_array($role, $user->roles)) {
                        $newUrl = $url;
                        break;
                    }
                }

                // If there weren't any matches, look for a wildcard
                if (! isset($newUrl) && array_key_exists('*', $roleUrls)) {
                    $newUrl = $roleUrls['*'];
                }

                if (isset($newUrl)) {
                    // Prepend with the home URL if the new URL is relative
                    if (preg_match('~^https?://~', $newUrl)) {
                        return esc_url($newUrl);
                    } else {
                        return esc_url(home_url('/' . ltrim($newUrl, '/')));
                    }
                }
            }

            // Use the default URL
            return $redirectUrl;
        }, 10, 3);

        return $this;
    }

    /**
     * Set a custom footer text in admin
     * @param string      $html  The text/html to display
     * @param string|null $place Where to place the text
     * Places:
     * 'before' = before the current text
     * 'after' = after the current text
     * null (or anything else) = overwrite
     * @return self
     */
    public function footerText(string $html, ?string $place = null): self
    {
        add_filter('admin_footer_text', function ($current) use ($html, $place) {
            if ($place == 'before') {
                return $html . ' ' . $current;
            } elseif ($place == 'after') {
                return $current . ' ' . $html;
            } else {
                return $html;
            }
        });

        return $this;
    }
}
