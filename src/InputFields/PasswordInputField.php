<?php

namespace Nerbiz\WordClass\InputFields;

class PasswordInputField extends AbstractInputField
{
    /**
     * {@inheritdoc}
     */
    public function renderField(): string
    {
        return sprintf(
            '<input type="password" class="regular-text" name="%s" value="%s">',
            $this->getFullName(),
            esc_attr(get_option($this->getFullName()))
        );
    }
}
