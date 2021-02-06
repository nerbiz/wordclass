<?php

namespace Nerbiz\WordClass\InputFields;

use Nerbiz\WordClass\Contracts\AbstractInputField;

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
            apply_filters('the_content', get_option($this->getPrefixedName())),
            $this->getPrefixedName(),
            [
                'wpautop'       => true,
                'media_buttons' => true,
                'textarea_name' => $this->getPrefixedName(),
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
        if (trim($this->description) !== '') {
            $this->description .= '<br>&nbsp;';
        }

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
