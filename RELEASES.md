# Release notes

## 0.9.3

* Various fixes, now supports PHP 7.2.

## 0.9.2

* Added Assets::jqueryVersion(), to replace the jQuery that Wordpress sets with a different version.

## 0.9.1

* Added Helpers::getPostMeta(), which is get_post_meta() with an implicit prefix, for convenience.
* Helpers::getMetaImage() now uses a prefix implicitly.

## 0.9.0

* Added: Metabox now sets a prefix to a metabox ID, and its field IDs.
* Added Theme::enableHtml5Support().
* Renamed Utilities::obscurePhoneLink() to obscurePhoneNumber() and added a parameter to force a link.
* Fixed: there was a small translation typo for 'add new #'.
* Using normal semver versioning from now on.

## 0.8.7.1

* Added release notes for v0.8.7

## 0.8.7

* Translating should now work properly.
  * Variables are not supported, so only the static strings are now translatable.
  * Added a .pot file to includes/languages, with Dutch translation (.mo and .po file).
  * Removed the CanSetTextDomain trait.
  * Removed the CanTranslate trait.
  * Removed the translation helper functions.
  * The text domain for Wordclass is set to 'wordclass'.

## 0.8.6

* Added Shortcodes::googleAnalytics().
* Added the CanTranslate trait, which is now used by all classes that translate (this enables passing a text domain as a variable, which is usually not possible).
* Added helper functions for translating, using CanTranslate.
* Translation strings for post types and taxonomies are now proper, with placeholders.
* SettingsPage now prefixes the settings URL.
* Shortcodes::addLabel() now supports empty lines (when no argument is passed to it).
* CMB2 is now included by Metabox, so no manual including anymore.

## 0.8.5

* Added Helpers::option(), for getting and setting options in the Wordpress options database table. Option prefix is automatically added.
* Added Helpers::deleteOption(), for removing an option from Wordpress options database table. Option prefix is automatically added.
* Added Init::autoload(), to make autoloading your own classes easy.
* Removed Init::autoloader(), because Wordclass should just be included with Composer.
* Fixed: SettingsPage wasn't working after the last update, section and field IDs were mixed up.

## 0.8.4

* Added Shortcodes::pageLink(), which creates the [page_link] shortcode (+ documentation).
* Shortcodes without parameters now insert into the editor without using a modal dialog.
* Shortcodes added with the Shortcodes class now actually output something -_-.
* Swapped $toolbar and $after parameters of Editor::addShortcodeButton().

## 0.8.3.3

* Fixed: all shortcode buttons used the options dialog of the last one. You can now add multiple shortcode buttons normally.

## 0.8.3.2

* Fixed: there was a hardcoded path in Editor, it's flexible now.

## 0.8.3.1

* The Shortcodes dropdown values now use value:label pairs.

## 0.8.3

* Major overhaul of Shortcodes: shortcodes now also add a button to the TinyMCE editor.
* Added the CanSetPrefix trait.
* Added Editor::addShortcodeButton(), which is usable, but supposed to only be used by Shortcodes.
* Updated documentation for Editor and Shortcodes.
* SettingsPage and PageBuilder now use the new CanSetPrefix trait.
* A default prefix can now be set with Init::defaultPrefix().
* The order of parameters of Editor methods is now more natural.

## 0.8.0

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

* Added Editor::addPlugin() to easily add a plugin to the TinyMCE editor.

## 0.7.0

* Added the Editor class, for modifying the TinyMCE editor.

## 0.6.12

* Removed the usage of 'plural' from PostType and Taxonomy, it just uses 'name' now.

## 0.6.11

* Added credits to the readme.
* Expanded the Shortcodes example.
* Fixed: non-existing array indexes error in Metabox.

## 0.6.10.1

* The settings group name is now optional in SettingsPage.

## 0.6.10

* Many improvements to SettingsPage
  * A separate page and menu item are now created, instead of a submenu of Settings.
  * ::add() has now been merged with the constructor.
  * Page slug is now always implicit (title converted to slug), but can be overwritten with ::pageSlug().
  * ::inputPrefix() and ::inputIdPrefix() are now combined as ::prefix(), which is now optional.
  * ::addSection() now also supports 'checkbox' and 'wysiwyg' input types.
  * See the updated docs for more information on the above.

## 0.6.9

* Added a missing 'static' keyword to some methods.
* Fixed: the 'include' method name caused an 'unexpected include' error.

## 0.6.8

* Default widgets sidebar description is now empty, and only translatable if given (in Widgets::addSidebar()).

## 0.6.7

* Admin::roleRedirects() now accepts a wildcard character ('*').
* Fixed: Admin::roleRedirects() didn't properly check the role, when role/url strings were given (not an array).

## 0.6.6

* Removed CanSetTextDomain::setTextDomain(), replaced by ::textDomain().
* CanSetTextDomain::textDomain() can now get and set the text domain.
* Added Init::defaultTextDomain(), which can set and get the default text domain.
* Text domain should now be called with static::textDomain(), instead of static::$_textDomain.

## 0.6.5

* Fixed: regex had no ending delimiter.

## 0.6.4

* Admin::showBar() now supports 'auto' setting (when no argument is given, same result as not calling this method).
* Admin::roleRedirects() now implicitly makes relative URLs when a given URL doesn't start with 'http(s)://'.
* Improved the URL regex in Assets.

## 0.6.3

* Plugins::include() now supports shorter syntax, see the docs for more info.
* Updated the Plugins documentations.

## 0.6.2

* Simplified Plugins, and using static methods now.
* Updated the Plugins documentation.

## 0.6.1

* Fixed: Plugins::include() wasn't chainable.

## 0.6.0

* !! Removed Autoloader.
* Added Init, which now holds the autoloader (Init::autoloader()).
* Init also has constants(), for convenience.
* Added documentation for Init.

## 0.5.1

* Fixed: Fixed: wrong method in Utilities documentation.

## 0.5.0

* Added the Plugins class, with documentation.

## 0.4.2

* Feature branch of the last release was not merged, should be good now.

## 0.4.1

* Added Composer dependencies.
* Enabled phone link (when on mobile) in Utilities::obscurePhoneLink().

## 0.4.0

Pretty big release this time.

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

* Fixed: Helpers image wrapper methods still used the old args order.

## 0.3

* Admin::roleRedirect() now also accepts 2 string arguments instead of 1 array, when only setting 1 redirect (see docs).
* Added a TODO file.
* Admin::hideBar is now called Admin::showBar(), with a boolean parameter.
* Fixed: arrays weren't handled properly by Assets::addAsset().
* Swapped the type/size parameters of Helpers image methods, because size is changed more often than type I think.
* Added documentation.

## 0.2.1

* Added the Autoloader again, for when not using Composer.
* Added Admin::roleRedirect(), to specify a custom URL to redirect to after login, based on user role.

## 0.2.0

* Taxonomy::getSlug() is now Helpers::getTaxonomySlug().
* Added Helpers::getTaxonomyItems().
* Added Helpers::getPostTaxonomies().
* Added these release notes.

## 0.1.3

* Added Theme::autoWindowTitle(), which lets Wordpress define the &lt;title&gt; tag.

## 0.1.2

* Removed Autoloader class, because Composer now handles that.

## 0.1.1

* Fixed: composer.json format error.

## 0.1.0

Initial release with many classes.
