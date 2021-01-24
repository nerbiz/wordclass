<?php

use Nerbiz\Wordclass\Admin;
use Nerbiz\Wordclass\Assets;
use Nerbiz\Wordclass\Editor;
use Nerbiz\Wordclass\Init;
use Nerbiz\Wordclass\Media;
use Nerbiz\Wordclass\Pages;
use Nerbiz\Wordclass\Theme;
use Nerbiz\Wordclass\Webpack;
// Example namespace, autoloaded from composer.json
use NewProject\YourPostTypesTaxonomies;
use NewProject\YourSettingsPage;

require get_stylesheet_directory() . '/vendor/autoload.php';

// Initial settings and values
Init::setPrefix('aaa');
(new Init())->loadTranslations()
    ->defineConstants()
    ->includeHelperFunctions();

// This path is the default,
// only shown here to illustrate that you can set a custom path
$manifestPath = get_stylesheet_directory() . '/dist/manifest.json';
Webpack::readManifest($manifestPath);

(new Assets())
    // Set a custom jQuery version (front-end only)
    ->jQueryVersion('3.5.1')
    // Or remove it (front-end only)
    ->removeJquery()
    // Add some assets to your pages
    ->addThemeCss('theme-front', get_stylesheet_uri() . '/dist/style.css')
    ->addThemeJs('theme-front', 'https://example.com/script.js')
    ->addAdminCss('theme-admin', Webpack::getAssetUrl('admin.css'))
    ->addAdminJs('theme-admin', Webpack::getAssetUrl('admin.js'))
    ->addLoginCss('theme-login', Webpack::getAssetUrl('login.css'))
    ->addLoginJs('theme-login', Webpack::getAssetUrl('login.js'))
    ->hashVersionParameters('your-salt-string');

// Create post types and/or taxonomies
// Moved to a separate class, to not pollute the functions.php file too much
$postTypesTaxonomies = new YourPostTypesTaxonomies();
$cptCalendarItem = $postTypesTaxonomies->createCalendarItemPostType();
$postTypesTaxonomies->createCalendarItemTagTaxonomy($cptCalendarItem);

// Set some theme options
(new Theme())
    // Don't show version information (for security)
    ->removeGeneratorMeta()
    // You can enable featured images for specific post types (array)
    // (PostType objects are supported)
    ->enableFeaturedImages(['page', $cptCalendarItem])
    // Or enable it for all post types (empty)
    ->enableFeaturedImages()
    ->enableHtml5Support()
    ->addMenus([
        'primary' => __('Main menu', 'project-text-domain'),
        'footer' => __('Footer menu', 'project-text-domain'),
    ]);

(new Media())
    ->setFeaturedImageSize(1024, 768)
    // Add some useful image sizes
    ->addImageSize('card', 'Card', 500, 500, false)
    ->addImageSize('maximum', 'Maximum', 1200, 1200, false)
    // Allow SVG files in the media library
    ->addUploadSupport('svg', 'image/svg+xml')
    ->temporaryHost('example.com');

(new Pages())
    ->automaticWindowTitle()
    // The part between site and page title, e.g. 'About us - Company name'
    // HTML is supported
    ->setWindowTitleSeparator('&middot;')
    // Disable search functionality if you wish
    ->disableSearchFunctionality()
    // Set page titles for 404 and search result pages
    ->set404PageTitle('Not found')
    ->setSearchPageTitle('Search results');

// Login and admin options
global $wp_version;
(new Admin())
    // Show a custom footer text in admin
    ->footerText(sprintf(
        '%s - <em>%s</em> (Wordpress v%s)',
        get_bloginfo('name'),
        get_bloginfo('description'),
        $wp_version
    ))
    // Redirect users after they login (by role)
    ->roleRedirect('editor', home_url('welcome'))
    // Add an admin bar button,
    // Which can move it to the bottom of the page
    ->addMoveAdminBarButton();

// Create a custom settings page
// Moved to a separate class, to not pollute the functions.php file too much
(new YourSettingsPage())->create();

// Adjust the TinyMCE editor (if you're not using Gutenberg)
(new Editor())
    ->removeH1FromFormats()
    ->forceAdvanced()
    // Add a 'bold' button on the second toolbar,
    // after the 'indent' button
    ->addButton('bold', 'indent', 2)
    // Or remove a button
    ->removeButton('bold')
    // Replace the 'bold' button with an 'indent' button
    ->replaceButton('bold', 'indent')
    // Add a 'table' button on the second toolbar,
    // after the 'indent' button
    ->addPlugin('table', 'indent', 2);
