# Release notes

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
