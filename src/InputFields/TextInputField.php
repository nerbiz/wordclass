<?php

namespace Nerbiz\Wordclass\InputFields;

class TextInputField extends AbstractInputField
{
    /**
     * {@inheritdoc}
     */
    public function renderField(): string
    {
        return sprintf(
            '<input type="text" class="regular-text" name="%s" value="%s">',
            $this->name,
            esc_attr(get_option($this->name))
        );
    }
}
