<?php

namespace Nerbiz\Wordclass;

class Admin
{
    /**
     * Redirect to a custom URL after login, based on role
     * @param string $role
     * @param string $url
     * @return self
     */
    public function roleRedirect(string $role, string $url): self
    {
        // Specific roles take precedence over a wildcard
        $priority = ($role === '*') ? 10 : 20;

        add_filter('login_redirect', function ($redirectUrl, $request, $user) use ($role, $url) {
            // Check if the user has roles
            if (isset($user->roles) && is_array($user->roles)) {
                // If there weren't any matches, look for a wildcard
                if ($role === '*') {
                    $newUrl = $url;
                } else if (in_array($role, $user->roles)) {
                    $newUrl = $url;
                } else {
                    $newUrl = null;
                }

                if ($newUrl !== null) {
                    if (preg_match('~^https?://~', $newUrl)) {
                        return esc_url($newUrl);
                    } else {
                        // Prepend a relative URL with the home URL
                        return esc_url(home_url('/' . ltrim($newUrl, '/')));
                    }
                }
            }

            // Use the default URL
            return $redirectUrl;
        }, $priority, 3);

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
            if ($place === 'before') {
                return $html . ' ' . $current;
            } elseif ($place === 'after') {
                return $current . ' ' . $html;
            } else {
                return $html;
            }
        });

        return $this;
    }

    /**
     * Add a button to the admin bar, for moving its location
     * @return self
     */
    public function addMoveAdminBarButton(): self
    {
        // Add the required styling and script
        $moveBarHandle = Init::getPrefix() . '-admin-bar';
        (new Assets())
            ->addThemeCss(
                $moveBarHandle,
                Init::getVendorUri('nerbiz/wordclass/includes/css/admin-bar.css')
            )
            ->addThemeJs(
                $moveBarHandle,
                Init::getVendorUri('nerbiz/wordclass/includes/js/admin-bar.js')
            );

        add_action('admin_bar_menu', function ($wpAdminBar) {
            if (! is_admin()) {
                $wpAdminBar->add_node([
                    'id' => 'adminbar-location-toggle',
                    'title' => '<span class="ab-icon dashicons dashicons-arrow-down-alt"></span>'
                        . __('Move bar', 'wordclass'),
                    'href' => '#',
                    'meta' => [
                        'class' => 'adminbar-location-toggle-button',
                    ],
                ]);
            }
        }, 100);

        return $this;
    }
}
