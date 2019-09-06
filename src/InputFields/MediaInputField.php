<?php

namespace Nerbiz\Wordclass\InputFields;

class MediaInputField extends AbstractInputField
{
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
