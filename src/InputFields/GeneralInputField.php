<?php

namespace Nerbiz\WordClass\InputFields;

class GeneralInputField extends AbstractInputField
{
    /**
     * {@inheritdoc}
     */
    public function renderField(): string
    {
        if (! isset($this->attributes['type'])) {
            $this->attributes['type'] = 'text';
        }

        return sprintf(
            '<input class="regular-text" name="%s" value="%s" %s>',
            $this->getFullName(),
            $this->getStoredValue(),
            $this->getAttributesString()
        );
    }
}
