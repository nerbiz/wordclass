<?php

namespace Nerbiz\Wordclass;

class AdminBar implements WordclassInterface
{
    /**
     * Indicates whether the required scripts are added
     * Prevents including twice
     * @var bool
     */
    protected static $moveBarScriptAdded = false;

    public function __construct()
    {
        // Add the required script
        if (! static::$moveBarScriptAdded) {
            $init = new Init();
            $assets = new Assets();
            $mediaUploadHandle = $init->getPrefix() . '-admin-bar';
            $assets->addThemeCss([
                $mediaUploadHandle => $init->getVendorUri('nerbiz/wordclass/includes/css/admin-bar.css'),
            ]);
            $assets->addThemeJs([
                $mediaUploadHandle => $init->getVendorUri('nerbiz/wordclass/includes/js/admin-bar.js'),
            ]);

            static::$moveBarScriptAdded = true;
        }
    }

    /**
     * Add a button to the bar, for moving its location
     * @return void
     */
    public function addMoveLocationButton(): void
    {
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
    }
}
