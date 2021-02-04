<?php

namespace Nerbiz\WordClass;

class Editor
{
    /**
     * Force the extra button rows of the TinyMCE editor to show
     * @param  bool $keepButton Keep the toggle button
     * @return self
     */
    public function forceAdvanced(bool $keepButton = false): self
    {
        // Remove the toggle button
        if (! $keepButton) {
            $this->removeButton('wp_adv', 1);
        }

        // Force the 2nd buttons row
        add_filter('tiny_mce_before_init', function ($args) {
            $args['wordpress_adv_hidden'] = false;
            return $args;
        });

        return $this;
    }

    /**
     * Get the buttons filter to use, based on the toolbar
     * @param  int $number The toolbar number, 1 = default, 2/3/4 = advanced
     * @return string
     */
    protected function getButtonsFilter(int $number): string
    {
        // Fallback, and the filter name for toolbar 1
        $filter = 'mce_buttons';

        // The advanced toolbars have their own filter names
        if (in_array($number, [2, 3, 4])) {
            $filter .= '_' . $number;
        }

        return $filter;
    }

    /**
     * Add a button to the TinyMCE editor
     * @param  string      $name          The name of the button
     * @param  string|null $after         The name of the button to place the new button after
     *   '_first' places the button as the first one
     *   null places the button at the end
     * @param  int         $toolbarNumber The toolbar number, 1 = default, 2/3/4 = advanced
     * @return self
     */
    public function addButton(string $name, ?string $after = null, int $toolbarNumber = 1): self
    {
        $filter = $this->getButtonsFilter($toolbarNumber);

        add_filter($filter, function ($buttons) use ($name, $after) {
            // Decide where to place the button
            if ($after === null) {
                $buttons[] = $name;
            } elseif ($after === '_first') {
                array_unshift($buttons, $name);
            }

            // Insert the button after an existing one
            else {
                $afterButtonKey = array_search($after, $buttons);

                // Append the button at the end, if the 'after' button is not found
                if ($afterButtonKey === false) {
                    $buttons[] = $name;
                } else {
                    array_splice($buttons, ($afterButtonKey + 1), 0, $name);
                }
            }

            return $buttons;
        });

        return $this;
    }

    /**
     * Remove or replace a button from the TinyMCE editor
     * @param  string $name          The name of the button to remove
     * @param  int    $toolbarNumber The toolbar number, 1 = default, 2/3/4 = advanced
     * @return self
     */
    public function removeButton(string $name, int $toolbarNumber = 1): self
    {
        $filter = $this->getButtonsFilter($toolbarNumber);

        add_filter($filter, function ($buttons) use ($name) {
            $removeButtonKey = array_search($name, $buttons);

            // Only remove/replace the button if it exists
            // Using array_splice(), because unset() doesn't reset array keys
            if ($removeButtonKey !== false) {
                array_splice($buttons, $removeButtonKey, 1);
            }

            return $buttons;
        });

        return $this;
    }

    /**
     * Remove or replace a button from the TinyMCE editor
     * @param  string $name          The name of the button to remove
     * @param  string $replaceWith   The name of the button to replace the removed one with
     * @param  int    $toolbarNumber The toolbar number, 1 = default, 2/3/4 = advanced
     * @return self
     */
    public function replaceButton(string $name, string $replaceWith, int $toolbarNumber = 1): self
    {
        $filter = $this->getButtonsFilter($toolbarNumber);

        add_filter($filter, function ($buttons) use ($name, $replaceWith) {
            $removeButtonKey = array_search($name, $buttons);

            // Only remove/replace the button if it exists
            // Using array_splice(), because unset() doesn't reset array keys
            if ($removeButtonKey !== false) {
                array_splice($buttons, $removeButtonKey, 1, $replaceWith);
            }

            return $buttons;
        });

        return $this;
    }

    /**
     * Move a button, optionally from one toolbar to another
     * @param  string      $name              The name of the button
     * @param  string|null $after             The name of the button to place the new button after
     *   '_first' places the button as the first one
     *   null places the button at the end
     * @param  int         $fromToolbarNumber The toolbar number, 1 = default, 2/3/4 = advanced
     * @param  int         $toToolbarNumber   The toolbar to move the button to, same toolbar if null
     * @return self
     */
    public function moveButton(
        string $name,
        ?string $after = null,
        int $fromToolbarNumber = 1,
        int $toToolbarNumber = 1
    ): self {
        $this->removeButton($name, $fromToolbarNumber);
        $this->addButton($name, $after, $toToolbarNumber);

        return $this;
    }

    /**
     * Add a TinyMCE plugin to the editor
     * @param  string      $name          The name of the plugin
     * @param  string|null $after         The name of the button to place the new button after
     *   '_first' places the button as the first one
     *   null places the button at the end
     * @param  int         $toolbarNumber The toolbar number, 1 = default, 2/3/4 = advanced, false to not add
     * @return self
     */
    public function addPlugin(string $name, ?string $after = null, int $toolbarNumber = 1): self
    {
        $pluginPath = 'tinymce/tinymce/plugins/' . $name . '/plugin.min.js';

        // The plugin needs to exist
        if (is_readable(Init::getVendorPath($pluginPath))) {
            // Add the plugin
            add_filter('mce_external_plugins', function ($plugins) use ($name, $pluginPath) {
                $plugins[$name] = Init::getVendorUri($pluginPath);
                return $plugins;
            });

            // Add a button if needed
            if ($toolbarNumber !== false) {
                $this->addButton($name, $after, $toolbarNumber);
            }
        }

        return $this;
    }

    /**
     * Prevent users from using an <h1> element in the editor
     * @return self
     */
    public function removeH1FromFormats(): self
    {
        add_filter('tiny_mce_before_init', function ($args) {
            $blockFormats = [
                'Paragraph=p',
                'Heading 2=h2',
                'Heading 3=h3',
                'Heading 4=h4',
                'Heading 5=h5',
                'Heading 6=h6',
                'Preformatted=pre',
            ];

            $args['block_formats'] = implode(';', $blockFormats);

            return $args;
        });

        return $this;
    }
}
