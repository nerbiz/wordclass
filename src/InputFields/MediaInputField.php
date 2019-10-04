<?php

namespace Nerbiz\Wordclass\InputFields;

use Nerbiz\Wordclass\Assets;
use Nerbiz\Wordclass\Init;

class MediaInputField extends AbstractInputField
{
    /**
     * Indicates whether the required scripts are added
     * Prevents including twice
     * @var bool
     */
    protected static $scriptsAdded = false;

    public function __construct(string $name, string $title, ?string $description = null)
    {
        // Add the required scripts (once)
        if (! static::$scriptsAdded) {
            $assets = new Assets();

            $mediaUploadHandle = Init::getPrefix() . '-media-upload';
            $assets->addAdminJs([
                $mediaUploadHandle => Init::getVendorUri('nerbiz/wordclass/includes/js/media-upload.js')
            ]);

            static::$scriptsAdded = true;
        }

        parent::__construct($name, $title, $description);
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
            esc_attr(get_option($this->name)),
            'thumbnail'
        );

        return sprintf(
            '<div class="media-upload-input">
                <div class="image-preview-wrapper">
                    <img class="image-preview" src="%s" width="100" height="100" style="border: 1px #ccc solid;">
                </div>
                <input type="button" class="button upload-media-button" value="%s">
                <a href="#" class="clear-media-button" style="margin-left: 20px; vertical-align: sub;">%s</a>
                <input type="hidden" name="%s" value="%s">
            </div>',
            $currentMediaUrl,
            __('Select media', 'wordclass'),
            __('Clear', 'wordclass'),
            $this->name,
            esc_attr(get_option($this->name))
        );
    }
}
