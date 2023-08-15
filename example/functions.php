<?php

use Nerbiz\WordClass\Admin;
use Nerbiz\WordClass\Assets\Assets;
use Nerbiz\WordClass\Assets\ViteAssets;
use Nerbiz\WordClass\Assets\WebpackAssets;
use Nerbiz\WordClass\Encrypter;
use Nerbiz\WordClass\Init;
use Nerbiz\WordClass\Mail;
use Nerbiz\WordClass\Media;
use Nerbiz\WordClass\Pages;
use Nerbiz\WordClass\Security;
use Nerbiz\WordClass\Theme;
// Classes in example namespace, autoloaded from composer.json
use YourNamespace\YourPostColumns;
use YourNamespace\YourPostTypesTaxonomies;
use YourNamespace\YourSettingsPage;

require get_stylesheet_directory() . '/vendor/autoload.php';

// Do this first, because options, post types, IDs and more things use this prefix
Init::setPrefix('xyz');

// English is default, load translations if needed
Init::loadTranslations();

(new Admin())
    // Redirect users after they log in (by role name)
    ->roleRedirect('editor', home_url('welcome'))
    // Show a custom footer text in admin
    ->footerText('This text is shown in the footer on each admin page')
    // Add button in the admin bar, that can move it to the bottom of the page
    ->makeAdminBarMovable();

(new Assets())
    // Load assets from a URL, can be external
    ->addThemeCss('theme-front', get_stylesheet_uri() . '/dist/style.css')
    ->addThemeJs('theme-external', 'https://example.com/external.js')
    // Extra options are possible using an array, for CSS it's 'media' instead of 'in_footer'
    ->addThemeJs('theme-front', [
        'uri' => get_stylesheet_uri() . '/dist/script.js',
        'deps' => ['theme-external'],
        'ver' => '1.2',
        'in_footer' => true,
    ])
    // In the same way, assets can be added to admin and login pages
    ->addAdminCss('theme-front', get_stylesheet_uri() . '/dist/admin.css')
    ->addAdminJs('theme-front', get_stylesheet_uri() . '/dist/admin.js')
    ->addLoginCss('theme-front', get_stylesheet_uri() . '/dist/login.css')
    ->addLoginJs('theme-front', get_stylesheet_uri() . '/dist/login.js');

// Webpack assets are supported, any 'add' method from Assets is available here,
// the difference is that it gets URLs from the manifest.
// This directory is the default, only shown here as a usage example
$distDirectory = get_stylesheet_directory() . '/dist';
(new WebpackAssets($distDirectory))
    ->addThemeCss('theme-front', 'style.css');

// Vite assets are also supported
(new ViteAssets())
    // Use this when you're running the Vite dev server (this address is the default)
    ->useDevServer('https://localhost:5173')
    ->addThemeCss('theme-front', 'style.scss');

$encrypter = new Encrypter('62Yvf/E0WB6RU1C2P5XZptpyIdTDleb69FIAO4TB9R4=');
(new Mail())
    // Make wp_mail() send using SMTP, and add an SMTP settings page as a submenu of settings.
    // The encrypter is used to store the SMTP password
    ->addSmtpSupport($encrypter)
    // Store all emails sent with wp_mail() as 'sent email' posts (separate post type)
    ->storeSentEmails();

(new Media())
    // Set the size and whether/how to crop (true) or resize (false), default is false.
    // Alternatively, the 3rd argument can be an array, specifying positioning of the crop area:
    // [x, y], where x = 'left', 'center' or 'right', y = 'top', 'center' or 'bottom'
    ->setFeaturedImageSize(1024, 768, false)
    // Image sizes need a name, label, width, height and whether/how to crop (default false),
    // the label is used in the sizes dropdown, when selecting an image
    ->addImageSize('card', 'Card', 500, 500, true)
    ->addImageSize('maximum', 'Maximum', 1200, 1200)
    // Not all filetypes can be uploaded by default,
    // you can enable support by providing an extension and MIME type
    ->enableUploadSupport('bmp', 'image/bmp')
    // Allow SVG files in the media library,
    // this also ensures the first line in an SVG file is an '<?xml' line
    ->enableSvgSupport()
    // This replaces the hostname in any attachment URL:
    // https://localhost/.../image.png -> https://example.com/.../image.png.
    // This can be useful when having a local copy of a website,
    // without needing to download a potentially huge uploads directory
    ->useRemoteAttachmentUrls('example.com')
    // Only enabled in 'local' and 'development' environments by default,
    // the possible environments are 'local', 'development', 'staging' and 'production'.
    // This method checks using the WP_ENVIRONMENT_TYPE constant
    ->useRemoteAttachmentUrls('example.com', ['local', 'development']);

(new Pages())
    // Let WordPress create and define the <title> tag in <head>
    ->automaticWindowTitle()
    // The window title separator, i.e. the hyphen in 'About us - Company name'
    // (HTML is supported)
    ->setWindowTitleSeparator('&middot;')
    // Set page titles for 404 and search result pages
    ->set404PageTitle('Not found')
    ->setSearchPageTitle('Search results')
    // Disable all search functionality
    ->disableSearch();

(new Security())
    // Don't create the 'generator' meta tag
    ->removeGeneratorMeta()
    // Replace 'ver=' values in asset URLs with a hash (needs a salt value)
    ->hashVersionParameters('5r1hm2KMaul/gOa')
    // Disable XML-RPC, it can be a security issue
    ->disableXmlRpc();

(new Theme())
    // Enable featured images for all post types
    ->enableFeaturedImages()
    // Or for specific post types (array), PostType objects are supported
    ->enableFeaturedImages(['post', 'page'])
    // Enable all HTML5 features
    ->enableHtml5Support()
    // Or an array of specific features
    ->enableHtml5Support(['search-form', 'gallery'])
    // Register menus as name/description pairs
    ->registerMenus([
        'primary' => 'Main menu',
        'footer' => 'Footer menu',
    ]);



/*
 * Some features require a bit more code,
 * so to keep this functions.php organized, it's in separate classes
 */

// Create a custom settings page
(new YourSettingsPage())->create();

// Create post types and taxonomies
$postTypesTaxonomies = new YourPostTypesTaxonomies();
$cptCalendarItem = $postTypesTaxonomies->createCalendarItemPostType();
$cptCalendarItemTag = $postTypesTaxonomies->createCalendarItemTagTaxonomy();

// Adjust the columns for the calendar items overview page
(new YourPostColumns())->register();
