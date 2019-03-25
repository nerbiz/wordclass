<?php

namespace Nerbiz\Wordclass\SettingInputs;

class Checkbox extends AbstractSettingInput
{
    /**
     * {@inheritdoc}
     */
    public function render(): string
    {
        return sprintf(
            '<input type="checkbox" name="%s" value="1" %s>',
            $this->arguments['name'],
            checked(1, get_option($this->arguments['name']), false)
        );
    }
}
