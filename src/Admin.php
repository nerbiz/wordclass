<?php

namespace Nerbiz\WordClass;

use Nerbiz\WordClass\Assets\Assets;
use WP_Admin_Bar;
use WP_Error;
use WP_User;

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
        add_filter('login_redirect', function (string $redirectUrl, string $request, WP_User|WP_Error $user) use ($role, $url) {
            // See if the role matches, or if there is a wildcard ('*')
            $userRoles = $user->roles ?? [];
            if ($user instanceof WP_User && ($role === '*' || in_array($role, $userRoles, true))) {
                return esc_url($url);
            }

            // In case of no match or no roles, use the default URL
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
        add_filter('admin_footer_text', function (string $current) use ($html, $place) {
            return match ($place) {
                'before' => $html . ' ' . $current,
                'after' => $current . ' ' . $html,
                default => $html,
            };
        });

        return $this;
    }

    /**
     * Add a button to the admin bar, for moving its location
     * @return self
     */
    public function makeAdminBarMovable(): self
    {
        // Add the required styling and script
        $moveBarHandle = Helpers::withPrefix('admin-bar', '-');
        $includesDirUrl = Init::getPackageUri('includes/');
        (new Assets())
            ->addThemeCss($moveBarHandle, $includesDirUrl . 'css/admin-bar.css')
            ->addThemeJs($moveBarHandle, $includesDirUrl . 'js/admin-bar.js');

        add_action('admin_bar_menu', function (WP_Admin_Bar $wpAdminBar) {
            // Don't change it on admin screens, only on front-end
            if (is_admin()) {
                return;
            }

            $wpAdminBar->add_node([
                'id' => 'wordclass-adminbar-location-toggle',
                'title' => '<span class="ab-icon dashicons dashicons-arrow-down-alt"></span>'
                    // translators: Button text for moving the admin bar
                    . __('Move bar', 'wordclass'),
                'href' => '#',
            ]);
        }, 100);

        return $this;
    }
}
