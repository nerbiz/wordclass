<?php

namespace Nerbiz\Wordclass\InputFields;

class PasswordInputField extends AbstractInputField
{
    /**
     * {@inheritdoc}
     */
    public function renderField(): string
    {
        return sprintf(
            '<input type="password" class="regular-text" name="%s" value="%s">',
            $this->getPrefixedName(),
            esc_attr(get_option($this->getPrefixedName()))
        );
    }
}
