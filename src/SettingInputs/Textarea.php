<?php

namespace Nerbiz\Wordclass\SettingInputs;

class Textarea extends AbstractSettingInput
{
    /**
     * {@inheritdoc}
     */
    public function render(): string
    {
        return sprintf(
            '<textarea class="regular-text" name="%s">%s</textarea>',
            $this->arguments['name'],
            esc_attr(get_option($this->arguments['name']))
        );
    }
}
