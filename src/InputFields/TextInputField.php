<?php

namespace Nerbiz\WordClass\InputFields;

use Nerbiz\WordClass\Contracts\AbstractInputField;

class TextInputField extends AbstractInputField
{
    /**
     * {@inheritdoc}
     */
    public function renderField(): string
    {
        return sprintf(
            '<input type="text" class="regular-text" name="%s" value="%s">',
            $this->getPrefixedName(),
            esc_attr(get_option($this->getPrefixedName()))
        );
    }
}
