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
            '<input type="password" class="regular-text" name="%s" value="%s" %s>',
            $this->getFullName(),
            $this->getStoredValue(),
            $this->getAttributesString()
        );
    }
}
