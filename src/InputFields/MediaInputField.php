<?php

namespace Nerbiz\WordClass\InputFields;

use Nerbiz\WordClass\Assets;
use Nerbiz\WordClass\Contracts\AbstractInputField;
use Nerbiz\WordClass\Init;

class MediaInputField extends AbstractInputField
{
    /**
     * Indicates whether the required scripts are added
     * Prevents including twice
     * @var bool
     */
    protected static $scriptsAdded = false;

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

        // The currently stored value
        $currentMediaUrl = wp_get_attachment_image_url(
            esc_attr(get_option($this->getPrefixedName())),
            'thumbnail'
        );

        // Other values for the template
        $currentMediaFilename = basename(get_attached_file(
            esc_attr(get_option($this->getPrefixedName()))
        ));
        $prefixedName = $this->getPrefixedName();
        $inputValue = esc_attr(get_option($this->getPrefixedName()));

        ob_start();
        require __DIR__ . '/../../includes/html/media-input-field.php';
        return ob_get_clean();
    }
}
