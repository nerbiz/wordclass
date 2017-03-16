<?php

namespace Wordclass;

class Admin {
    /**
     * Hide the admin bar, when viewing the website
     */
    public static function hideBar() {
        show_admin_bar(false);
    }
}
