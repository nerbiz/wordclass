<?php

namespace Nerbiz\WordClass\InputFields;

class CheckboxInputField extends AbstractInputField
{
    /**
     * {@inheritdoc}
     */
    protected function renderField(): string
    {
        return sprintf(
            '<input type="checkbox" name="%s" value="1" %s %s>',
            $this->getFullName(),
            checked(1, $this->getStoredValue(), false),
            $this->getAttributesString()
        );
    }
}
