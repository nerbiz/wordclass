<?php

namespace Nerbiz\Wordclass\SettingInputs;

class Media extends AbstractSettingInput
{
    /**
     * {@inheritdoc}
     */
    public function render(): string
    {
        // Enqueue the required scripts
        wp_enqueue_media();

        // The currently stored value
        $currentMediaUrl = trim(wp_get_attachment_image_url(get_option($this->arguments['name'])));

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
            $this->arguments['name'],
            get_option($this->arguments['name'])
        );
    }
}
