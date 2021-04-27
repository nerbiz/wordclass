<?php

use Nerbiz\WordClass\Admin;
use Nerbiz\WordClass\Assets;
use Nerbiz\WordClass\Editor;
use Nerbiz\WordClass\Init;
use Nerbiz\WordClass\Mail;
use Nerbiz\WordClass\Media;
use Nerbiz\WordClass\Pages;
use Nerbiz\WordClass\Theme;
use Nerbiz\WordClass\Webpack;
use Nerbiz\WordClass\Yoast;
// Example namespace, autoloaded from composer.json
use NewProject\YourPostColumns;
use NewProject\YourPostTypesTaxonomies;
use NewProject\YourSettingsPage;

require get_stylesheet_directory() . '/vendor/autoload.php';

// Initial settings and values
Init::setPrefix('aaa');
(new Init())->loadTranslations()
    ->defineConstants()
    ->includeHelperFunctions();

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

// This path is the default,
// only shown here to illustrate that you can set a custom path
$manifestPath = get_stylesheet_directory() . '/dist/manifest.json';
Webpack::readManifest($manifestPath);

(new Assets())
    // Add some assets to your pages
    ->addThemeCss('theme-front', get_stylesheet_uri() . '/dist/style.css')
    ->addThemeJs('theme-front', 'https://example.com/script.js')
    ->addAdminCss('theme-admin', Webpack::getAssetUrl('admin.css'))
    ->addAdminJs('theme-admin', Webpack::getAssetUrl('admin.js'))
    ->addLoginCss('theme-login', Webpack::getAssetUrl('login.css'))
    ->addLoginJs('theme-login', Webpack::getAssetUrl('login.js'))
    // Replace '?ver=...' with a hash for security, hiding the WordPress version
    ->hashVersionParameters('your-salt-string');

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

(new Mail())
    // Add support and a settings page for SMTP
    ->addSmtpSupport('your-encryption-key')
    // Store all sent emails as posts
    ->storeSentEmails();

(new Media())
    ->setFeaturedImageSize(1024, 768)
    // Add some useful image sizes
    ->addImageSize('card', 'Card', 500, 500, false)
    ->addImageSize('maximum', 'Maximum', 1200, 1200, false)
    // Allow SVG files in the media library
    ->addSvgSupport()
    // '.bmp' files are probably supported already,
    // this is just for illustrating how to add media support
    ->addUploadSupport('bmp', 'image/bmp')
    // Use a remote host for all media, for local development with remote media
    ->temporaryHost('example.com');

(new Pages())
    ->automaticWindowTitle()
    // The part between site and page title, e.g. 'About us - Company name'
    // HTML is supported
    ->setWindowTitleSeparator('&middot;')
    // Disable search functionality if you wish
    ->disableSearchFunctionality()
    // Set page titles for 404 and search result pages
    ->set404PageTitle(__('Not found', 'project-text-domain'))
    ->setSearchPageTitle(__('Search results', 'project-text-domain'));

// Adjust the columns for the calendar items overview page
(new YourPostColumns())->register();

// Create post types and/or taxonomies
// Moved to a separate class, to not pollute the functions.php file too much
$postTypesTaxonomies = new YourPostTypesTaxonomies();
$cptCalendarItem = $postTypesTaxonomies->createCalendarItemPostType();
$postTypesTaxonomies->createCalendarItemTagTaxonomy($cptCalendarItem);

// Create a custom settings page
// Moved to a separate class, to not pollute the functions.php file too much
(new YourSettingsPage())->create();

// Set some theme options
(new Theme())
    // Don't show version information (for security)
    ->removeGeneratorMeta()
    // You can enable featured images for specific post types (array)
    // (PostType objects are supported)
    ->enableFeaturedImages(['page', $cptCalendarItem])
    // Or enable it for all post types
    ->enableFeaturedImages()
    ->enableHtml5Support()
    ->addMenus([
        'primary' => __('Main menu', 'project-text-domain'),
        'footer' => __('Footer menu', 'project-text-domain'),
    ]);

// Add a breadcrumb, in case you're using Yoast breadcrumbs:
// In this case a breadcrumb will be added to all calendar items,
// linking to an example post with ID 123
// Resulting in this:
// Home > [added] > Calendar item
(new Yoast())->addBreadcrumb($cptCalendarItem, 123);
