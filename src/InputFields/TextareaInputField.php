<?php

namespace Nerbiz\WordClass\InputFields;

class TextareaInputField extends AbstractInputField
{
    /**
     * {@inheritdoc}
     */
    public function renderField(): string
    {
        return sprintf(
            '<textarea class="regular-text" name="%s" %s>%s</textarea>',
            $this->getFullName(),
            $this->getAttributesString(),
            $this->getStoredValue()
        );
    }
}
