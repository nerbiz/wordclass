<?php

namespace Nerbiz\WordClass\InputFields;

class EditorInputField extends AbstractInputField
{
    /**
     * {@inheritdoc}
     */
    public function renderField(): string
    {
        // Buffer the output, because wp_editor() echoes
        ob_start();

        $inputName = $this->getFullName();

        wp_editor(
            apply_filters('the_content', get_option($inputName)),
            $inputName,
            [
                'wpautop'       => true,
                'media_buttons' => true,
                'textarea_name' => $inputName,
                'editor_height' => 200
            ]
        );

        return ob_get_clean();
    }

    /**
     * {@inheritdoc}
     */
    protected function prependRender(): string
    {
        $description = trim($this->description);

        if ($description !== '') {
            // Add a newline to the description text
            $this->description = $description . '<br>&nbsp;';
        }

        // Prepend the description, instead of appending
        return parent::appendRender();
    }

    /**
     * {@inheritdoc}
     */
    protected function appendRender(): string
    {
        return '';
    }
}
