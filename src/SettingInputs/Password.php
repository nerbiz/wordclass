<?php

namespace Nerbiz\Wordclass\SettingInputs;

class Password extends AbstractSettingInput
{
    /**
     * {@inheritdoc}
     */
    public function render(): string
    {
        return sprintf(
            '<input type="password" class="regular-text" name="%s" value="%s">',
            $this->arguments['name'],
            esc_attr(get_option($this->arguments['name']))
        );
    }
}
