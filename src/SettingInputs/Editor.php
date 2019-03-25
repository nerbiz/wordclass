<?php

namespace Nerbiz\Wordclass\SettingInputs;

class Editor extends AbstractSettingInput
{
    /**
     * {@inheritdoc}
     */
    public function render(): string
    {
        // Buffer the output, because wp_editor() echoes
        ob_start();

        wp_editor(
            apply_filters('the_content', get_option($this->arguments['name'])),
            $this->arguments['name'],
            [
                'wpautop'       => true,
                'media_buttons' => true,
                'textarea_name' => $this->arguments['name'],
                'editor_height' => 200
            ]
        );

        return ob_get_clean();
    }
}
