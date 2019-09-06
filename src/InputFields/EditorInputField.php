<?php

namespace Nerbiz\Wordclass\InputFields;

class EditorInputField extends AbstractInputField
{
    /**
     * {@inheritdoc}
     */
    public function renderField(): string
    {
        // Buffer the output, because wp_editor() echoes
        ob_start();

        wp_editor(
            apply_filters('the_content', get_option($this->name)),
            $this->name,
            [
                'wpautop'       => true,
                'media_buttons' => true,
                'textarea_name' => $this->name,
                'editor_height' => 200
            ]
        );

        return ob_get_clean();
    }
}
