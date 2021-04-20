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
            '<input type="checkbox" name="%s" value="1" %s>',
            $this->getPrefixedName(),
            checked(1, esc_attr(get_option($this->getPrefixedName())), false)
        );
    }
}
