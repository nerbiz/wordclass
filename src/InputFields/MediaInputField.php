<?php

namespace Nerbiz\WordClass\InputFields;

use Nerbiz\WordClass\Assets\Assets;
use Nerbiz\WordClass\Init;

class MediaInputField extends AbstractInputField
{
    /**
     * Indicates whether the required scripts are added
     * Prevents including twice
     * @var bool
     */
    protected static bool $scriptsAdded = false;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $name, string $label, ?string $description = null)
    {
        // Add the required scripts (once)
        if (! static::$scriptsAdded) {
            (new Assets())->addAdminJs(
                Init::getPrefix() . '-media-upload',
                Init::getVendorUri('nerbiz/wordclass/includes/js/media-upload.js')
            );

            static::$scriptsAdded = true;
        }

        parent::__construct($name, $label, $description);
    }

    /**
     * {@inheritdoc}
     */
    public function renderField(): string
    {
        // Enqueue the required scripts
        wp_enqueue_media();

        $storedValue = $this->getStoredValue();

        // The currently stored value
        $currentMediaUrl = wp_get_attachment_image_url($storedValue, 'thumbnail');

        // Other values for the template
        $currentMediaFilename = basename(get_attached_file($storedValue));
        $inputName = $this->getFullName();
        $inputValue = $storedValue;

        ob_start();
        require dirname(__FILE__, 3) . '/includes/html/media-input-field.php';
        return ob_get_clean();
    }
}
