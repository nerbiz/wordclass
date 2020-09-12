# Release notes

## 2.2.4
#### 2020-09-12

* Fixed: deprecation notice for old PHPMailer, now using new one.

## 2.2.3
#### 2020-08-01

* Added an example for implementing in functions.php.

## 2.2.2
#### 2020-08-01

* Converted the 'move admin bar' script to pure JavaScript, without jQuery.

## 2.2.1
#### 2020-08-01

* Deprecated Init::autoload().

## 2.2.0
#### 2020-06-20

* Added Media::addUploadSupport().
* Added Theme::removeGeneratorMeta().
* Moved media-related methods to the new Media class (and deprecated the old ones).
* Now using proper capitalization of the name 'WordPress'.

## 2.1.3
#### 2020-05-02

* URLs from Webpack::getAssetUrl() are now full URLs, using home_url().

## 2.1.2
#### 2020-02-02

* SMTP testmail (using Mail class) now uses wp_mail() and shows proper errors.

## 2.1.1
#### 2020-01-30

* Fixed: Mail was using removed SettingsPage and Helpers methods.
* Fixed: SettingsPage submit buttons didn't have unique names.

## 2.1.0
#### 2020-01-26

* Added the Pages class.
* Option::get() now supports a fallback value, in case the value is empty.
* Deprecated Theme::automaticTitle() in favour of Pages::automaticWindowTitle().

## 2.0.0
#### 2019-12-21

* Version 2.0.0 is now out of beta.

## 2.0.0-beta.6
#### 2019-11-20

* Renamed 'first' in Editor to '_first', to prevent naming conflicts.
* Yoast::addBreadcrumb() now supports PostType objects.
* Webpack is now completely static, and renamed its methods.
* Removed Shortcode, Premade\Shortcode, Editor::addShortcodesDropdown() and the custom TinyMCE plugin for that dropdown.
* Removed a couple of Utilities methods.
* Small improvements to code, making it better or more logical.
* SettingsPage capability required for working with settings pages can now be set by role name.

## 2.0.0-beta.5
#### 2019-11-18

* Fixed: SettingsPage nonce broke saving of other settings.
* Fixed: settings values were adding slashes.

## 2.0.0-beta.4
#### 2019-11-18

* Moved option-related methods from Helpers to the new Options class.
* Moved AdminBar code to the Admin class, and removed AdminBar.
* Crypto now uses a fixed cipher ('aes-256-cbc').
* Crypto now has a default encryption key (the SECURE_AUTH_KEY constant), though it's recommended to generate a new one.
* Added a constructor to PostType and Taxonomy.
* More logical code in PostType and Txonomy, and renamed create() to register().
* Custom post columns are now not sortable by default.
* Better readable code, more logical parameters.
* Other small improvements.

## 2.0.0-beta.3
#### 2019-11-11

* Settings pages don't use the Settings API anymore.
* SettingsPage now supports custom capability, to allow editors to edit settings, for instance.

## 2.0.0-beta.2
#### 2019-10-05

* Fixed: Mail wasn't using the new InputField classes.

## 2.0.0-beta.1
#### 2019-10-05

* Input fields now have the section ID as a prefix (example: xx_sectionId_inputName, where 'xx' is the prefix in Init).
* Static properties are now accessed statically.
* Utilities methods are now static.
* Added the Pool class (Multiton pattern), replaces Factory::reuse().
* Removed SettingInputs classes, in favour of the new InputField classes.
* Removed Factory class, in favour of the new Pool class.
* Removed the WordclassInterface.
* Removed the unneeded 'copyright' premade shortcode.
* Other small improvements.

## 1.8.3
#### 2019-10-04

* Added new InputField classes, SettingsPage now also supports those.

## 1.8.2
#### 2019-09-02

* SMTP support can now be added with 1 method call.

## 1.8.1
#### 2019-09-02

* Updated translations (Dutch).

## 1.8.0
#### 2019-09-02

* Added the Mail and Crypto classes, for using SMTP with all WP mail.
* Added the 'password' settings page input type.
* Added support for descriptions below settings input fields.
* Fixed: different settings pages could have the same settings group, resulting in values being lost.
* Fixed: settings page messages showed twice in some cases.

## 1.7.2
#### 2019-09-02

* SettingsPage now supports being a subpage.

## 1.7.1
#### 2019-08-26

* Removed an unnecessary is_callable() check.

## 1.7.0
#### 2019-08-26

* Added the Yoast class.
* Added the PostColumnsEditor and PostColumn classes.
* Converted internal factory usage to normal 'new' statements.
* Small adjustments to README.md.

## 1.6.0
#### 2019-08-25

* The current tab is now remembered on settings pages, after saving.
* Added Factory::reuse() to the PhpStorm meta helper file.
* Removed the unneeded Helpers::getPostTaxonomies() and Helpers::getTaxonomyItems().
* Added release dates to the RELEASES.md file.

## 1.5.0
#### 2019-07-21

* Added a Multiton pattern to Factory.
* Added the WordclassInterface for a stricter Factory.
* Added a helper functions file, for easier use of Helpers.
* Removed Helpers methods that didn't work since removing the Metabox class.

## 1.4.1
#### 2019-07-19

* Fixed: added missing CSS for moving the admin bar.

## 1.4.0
#### 2019-07-19

* Added the AdminBar class, with method to move the admin bar.
* Added an extra function for registering a taxonomy. See the [docs](https://codex.wordpress.org/Function_Reference/register_taxonomy) under 'Usage'.

## 1.3.1
#### 2019-07-13

* Added Init::setTimezone(), uses the 'timezone_string' Wordpress option by default.

## 1.3.0
#### 2019-07-12

* SettingsPage now renders a page with tabs per section.

## 1.2.5
#### 2019-07-06

* Fixed: Helpers::getFeaturedImage() didn't always pass an int to Helpers::getImage().

## 1.2.4
#### 2019-07-06

* Added the textarea input type for settings pages.

## 1.2.3
#### 2019-05-24

* Removed many default taxonomy arguments, relying on WP defaults instead.

## 1.2.2
#### 2019-04-18

* Fixed: the premade page link shortcode didn't show all pages in the TinyMCE modal.

## 1.2.1
#### 2019-04-18

* Fixed: vendor URI broke when Wordpress is in a subdirectory.

## 1.2.0
#### 2019-03-31

* Updated the PHP requirement to version 7.1.
* Added a media input type for a settings page.
* Added Init::loadTranslations().
* Added Editor::removeH1FromFormats().
* 'supports' is now a separate property in PostType, with its own setter.
* Removed the Metabox class, because it was actually limiting the [CMB2](https://github.com/CMB2/CMB2) functionality.
* Removed many default post type arguments, relying on WP defaults instead.
* Fixed: wrong method of converting to array.
* Fixed: Init::getVendorPath() didn't return the right path when another Composer directory existed.
* Fixed: couldn't submit settings page (error: options page not found).

## 1.1.2
#### 2018-11-06

* Removed the replacement option from Editor::removeButton(), just use Editor::replaceButton() instead.

## 1.1.1
#### 2018-11-04

* Fixed: Webpack was emptying the parsed manifest.
* Added Assets::removeJquery().
* Removed cache busting from Assets, using the default Wordpress way instead.

## 1.1.0
#### 2018-11-04

* Added a default prefix to Init, and the constants now use that prefix.
* Removed the 'docs' directory, I'm creating a wiki instead.

## 1.0.1
#### 2018-11-04

* Init::getVendorPath() and Init::getVendorUri() are not static anymore (but the properties still are).
* The inputs of SettingsPage now accept an arguments array, like before.

## 1.0.0
#### 2018-11-04

* Changed the namespace from Wordclass to Nerbiz\Wordclass.
* Added a Factory for creating objects.
* Added a meta file for Factory code completion in PhpStorm.
* Added Webpack, which parses a manifest for easy asset loading.
* Init now also contains the path to the vendor directory (not only the URI).
* Prefixed the default constants.
* Shortcodes are now placed in a dropdown in the editor. There used to be a separate button for each of them.
* Converted Shortcodes to Shortcode.
* Added the Premade namespace, now only containing pre-made shortcodes.
* The editor now accepts an array of Shortcode objects, to add in the TinyMCE toolbar.
* There is now 1 prefix, set with Init. A prefix used to be settable per class.
* PostType now accepts Taxonomy objects, apart from just strings (IDs).
* Simplified Utilities.
* Many code improvements, including converting to PSR-2 coding style.
* Removed all static usage, except in places where it made sense.
* Removed the Google Analytics code snippet (and pre-made shortcode).
* Removed Admin::showBar().
* Removed PageBuilder, because I don't want to work with page builders anymore, so I can't be bothered with installing it and testing this class.
* Removed Widgets, because it didn't add anything useful, it was mostly a wrapper.
* Removed the traits.
* Updated the readme file.

## 0.9.2
#### 2017-12-04

* Added Assets::jqueryVersion(), to replace the jQuery that Wordpress sets with a different version.

## 0.9.1
#### 2017-12-04

* Added Helpers::getPostMeta(), which is get_post_meta() with an implicit prefix, for convenience.
* Helpers::getMetaImage() now uses a prefix implicitly.

## 0.9.0
#### 2017-10-29

* Added: Metabox now sets a prefix to a metabox ID, and its field IDs.
* Added Theme::enableHtml5Support().
* Renamed Utilities::obscurePhoneLink() to obscurePhoneNumber() and added a parameter to force a link.
* Fixed: there was a small translation typo for 'add new #'.
* Using normal semver versioning from now on.

## 0.8.7.1
#### 2017-10-09

* Added release notes for v0.8.7

## 0.8.7
#### 2017-10-09

* Translating should now work properly.
  * Variables are not supported, so only the static strings are now translatable.
  * Added a .pot file to includes/languages, with Dutch translation (.mo and .po file).
  * Removed the CanSetTextDomain trait.
  * Removed the CanTranslate trait.
  * Removed the translation helper functions.
  * The text domain for Wordclass is set to 'wordclass'.

## 0.8.6
#### 2017-10-08

* Added Shortcodes::googleAnalytics().
* Added the CanTranslate trait, which is now used by all classes that translate (this enables passing a text domain as a variable, which is usually not possible).
* Added helper functions for translating, using CanTranslate.
* Translation strings for post types and taxonomies are now proper, with placeholders.
* SettingsPage now prefixes the settings URL.
* Shortcodes::addLabel() now supports empty lines (when no argument is passed to it).
* CMB2 is now included by Metabox, so no manual including anymore.

## 0.8.5
#### 2017-10-07

* Added Helpers::option(), for getting and setting options in the Wordpress options database table. Option prefix is automatically added.
* Added Helpers::deleteOption(), for removing an option from Wordpress options database table. Option prefix is automatically added.
* Added Init::autoload(), to make autoloading your own classes easy.
* Removed Init::autoloader(), because Wordclass should just be included with Composer.
* Fixed: SettingsPage wasn't working after the last update, section and field IDs were mixed up.

## 0.8.4
#### 2017-09-29

* Added Shortcodes::pageLink(), which creates the [page_link] shortcode (+ documentation).
* Shortcodes without parameters now insert into the editor without using a modal dialog.
* Shortcodes added with the Shortcodes class now actually output something -_-.
* Swapped $toolbar and $after parameters of Editor::addShortcodeButton().

## 0.8.3.3
#### 2017-09-29

* Fixed: all shortcode buttons used the options dialog of the last one. You can now add multiple shortcode buttons normally.

## 0.8.3.2
#### 2017-09-29

* Fixed: there was a hardcoded path in Editor, it's flexible now.

## 0.8.3.1
#### 2017-09-25

* The Shortcodes dropdown values now use value:label pairs.

## 0.8.3
#### 2017-09-24

* Major overhaul of Shortcodes: shortcodes now also add a button to the TinyMCE editor.
* Added the CanSetPrefix trait.
* Added Editor::addShortcodeButton(), which is usable, but supposed to only be used by Shortcodes.
* Updated documentation for Editor and Shortcodes.
* SettingsPage and PageBuilder now use the new CanSetPrefix trait.
* A default prefix can now be set with Init::defaultPrefix().
* The order of parameters of Editor methods is now more natural.

## 0.8.0
#### 2017-09-24

* Added the new Fixes class (+ docs), which contains fixes for general Wordpress errors, will add more in the future.
* Added Admin::footerText(), for changing the footer text on wp-admin.
* Added Init::vendorUri(), to specify a custom URI of the vendor (Composer) directory with, if needed.
* Added TinyMCE to the credits in the readme file.
* Renamed Taxonomy::forPostTypes() to Taxonomy::forPostType(), because it's usually 1 post type.
* Fixed a small documentation error for Editor.
* PostType and Taxonomy now use the names instead of 'New Item', ('New Movie' or 'New Genre' for instance).
* Editor now supports 3 extra button rows (4 in total).
* Simplified the Editor docs.

## 0.7.1
#### 2017-09-15

* Added Editor::addPlugin() to easily add a plugin to the TinyMCE editor.

## 0.7.0
#### 2017-09-14

* Added the Editor class, for modifying the TinyMCE editor.

## 0.6.12
#### 2017-06-01

* Removed the usage of 'plural' from PostType and Taxonomy, it just uses 'name' now.

## 0.6.11
#### 2017-05-27

* Added credits to the readme.
* Expanded the Shortcodes example.
* Fixed: non-existing array indexes error in Metabox.

## 0.6.10.1
#### 2017-05-20

* The settings group name is now optional in SettingsPage.

## 0.6.10
#### 2017-05-20

* Many improvements to SettingsPage
  * A separate page and menu item are now created, instead of a submenu of Settings.
  * ::add() has now been merged with the constructor.
  * Page slug is now always implicit (title converted to slug), but can be overwritten with ::pageSlug().
  * ::inputPrefix() and ::inputIdPrefix() are now combined as ::prefix(), which is now optional.
  * ::addSection() now also supports 'checkbox' and 'wysiwyg' input types.
  * See the updated docs for more information on the above.

## 0.6.9
#### 2017-05-14

* Added a missing 'static' keyword to some methods.
* Fixed: the 'include' method name caused an 'unexpected include' error.

## 0.6.8
#### 2017-05-14

* Default widgets sidebar description is now empty, and only translatable if given (in Widgets::addSidebar()).

## 0.6.7
#### 2017-05-14

* Admin::roleRedirects() now accepts a wildcard character ('*').
* Fixed: Admin::roleRedirects() didn't properly check the role, when role/url strings were given (not an array).

## 0.6.6
#### 2017-05-14

* Removed CanSetTextDomain::setTextDomain(), replaced by ::textDomain().
* CanSetTextDomain::textDomain() can now get and set the text domain.
* Added Init::defaultTextDomain(), which can set and get the default text domain.
* Text domain should now be called with static::textDomain(), instead of static::$_textDomain.

## 0.6.5
#### 2017-05-13

* Fixed: regex had no ending delimiter.

## 0.6.4
#### 2017-05-13

* Admin::showBar() now supports 'auto' setting (when no argument is given, same result as not calling this method).
* Admin::roleRedirects() now implicitly makes relative URLs when a given URL doesn't start with 'http(s)://'.
* Improved the URL regex in Assets.

## 0.6.3
#### 2017-05-13

* Plugins::include() now supports shorter syntax, see the docs for more info.
* Updated the Plugins documentations.

## 0.6.2
#### 2017-05-13

* Simplified Plugins, and using static methods now.
* Updated the Plugins documentation.

## 0.6.1
#### 2017-05-13

* Fixed: Plugins::include() wasn't chainable.

## 0.6.0
#### 2017-05-13

* !! Removed Autoloader.
* Added Init, which now holds the autoloader (Init::autoloader()).
* Init also has constants(), for convenience.
* Added documentation for Init.

## 0.5.1
#### 2017-05-06

* Fixed: Fixed: wrong method in Utilities documentation.

## 0.5.0
#### 2017-05-06

* Added the Plugins class, with documentation.

## 0.4.2
#### 2017-05-06

* Feature branch of the last release was not merged, should be good now.

## 0.4.1
#### 2017-05-06

* Added Composer dependencies.
* Enabled phone link (when on mobile) in Utilities::obscurePhoneLink().

## 0.4.0
#### 2017-05-06

* Finished documenting the whole package.
* The 'desc' field of a metabox item can now be translated.
* Renamed PageBuilder::addPanelRowStyle to addRowStyles().
* Replaced addPanelGridClasses() and addPanelRowClass() with addRowClasses().
* Removed pointless get() methods from PostType and Taxonomy.
* Removed the unused PostType::hasArchive().
* $subtitle parameter in SettingsPage::addSection() is now a string.
* Fixed: Assets was using a constant that wasn't in the package.
* Theme::addMenus() now supports adding a single menu.
* Renamed Admin::roleRedirect to roleRedirects().
* Added Taxonomy::description().
* Removed some Utilities methods().
* Renamed 'encrypted' occurences to 'obscured', in Utilities.
* Utilities::stringToBinary() now returns a string without spaces.

## 0.3.1
#### 2017-03-30

* Fixed: Helpers image wrapper methods still used the old args order.

## 0.3
#### 2017-03-30

* Admin::roleRedirect() now also accepts 2 string arguments instead of 1 array, when only setting 1 redirect (see docs).
* Added a TODO file.
* Admin::hideBar is now called Admin::showBar(), with a boolean parameter.
* Fixed: arrays weren't handled properly by Assets::addAsset().
* Swapped the type/size parameters of Helpers image methods, because size is changed more often than type I think.
* Added documentation.

## 0.2.1
#### 2017-03-20

* Added the Autoloader again, for when not using Composer.
* Added Admin::roleRedirect(), to specify a custom URL to redirect to after login, based on user role.

## 0.2.0
#### 2017-03-19

* Taxonomy::getSlug() is now Helpers::getTaxonomySlug().
* Added Helpers::getTaxonomyItems().
* Added Helpers::getPostTaxonomies().
* Added these release notes.

## 0.1.3
#### 2017-03-19

* Added Theme::autoWindowTitle(), which lets Wordpress define the &lt;title&gt; tag.

## 0.1.2
#### 2017-03-16

* Removed Autoloader class, because Composer now handles that.

## 0.1.1
#### 2017-03-16

* Fixed: composer.json format error.

## 0.1.0
#### 2017-03-16

Initial release with many classes.
