# Release notes

## 0.6.2

* Simplified Plugins, and using static methods now.
* Updated the Plugins documentation.

---

## 0.6.1

* Fixed: Plugins::include() wasn't chainable.

---

## 0.6.0

* !! Removed Autoloader.
* Added Init, which now holds the autoloader (Init::autoloader()).
* Init also has constants(), for convenience.
* Added documentation for Init.

---

## 0.5.1

* Fixed: Fixed: wrong method in Utilities documentation.

---

## 0.5.0

* Added the Plugins class, with documentation.

---

## 0.4.2

* Feature branch of the last release was not merged, should be good now.

---

## 0.4.1

* Added Composer dependencies.
* Enabled phone link (when on mobile) in Utilities::obscurePhoneLink().

---

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

---

## 0.3.1

* Fixed: Helpers image wrapper methods still used the old args order.

---

## 0.3

* Admin::roleRedirect() now also accepts 2 string arguments instead of 1 array, when only setting 1 redirect (see docs).
* Added a TODO file.
* Admin::hideBar is now called Admin::showBar(), with a boolean parameter.
* Fixed: arrays weren't handled properly by Assets::addAsset().
* Swapped the type/size parameters of Helpers image methods, because size is changed more often than type I think.
* Added documentation.

---

## 0.2.1

* Added the Autoloader again, for when not using Composer.
* Added Admin::roleRedirect(), to specify a custom URL to redirect to after login, based on user role.

---

## 0.2.0

* Taxonomy::getSlug() is now Helpers::getTaxonomySlug().
* Added Helpers::getTaxonomyItems().
* Added Helpers::getPostTaxonomies().
* Added these release notes.

---

## 0.1.3

* Added Theme::autoWindowTitle(), which lets Wordpress define the &lt;title&gt; tag.

---

## 0.1.2

* Removed Autoloader class, because Composer now handles that.

---

## 0.1.1

* Fixed: composer.json format error.

---

## 0.1.0

Initial release with many classes.
