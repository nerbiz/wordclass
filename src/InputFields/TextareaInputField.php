<?php

namespace Nerbiz\WordClass\InputFields;

use Nerbiz\WordClass\Contracts\AbstractInputField;

class TextareaInputField extends AbstractInputField
{
    /**
     * {@inheritdoc}
     */
    public function renderField(): string
    {
        return sprintf(
            '<textarea class="regular-text" name="%s">%s</textarea>',
            $this->getPrefixedName(),
            esc_attr(get_option($this->getPrefixedName()))
        );
    }
}
