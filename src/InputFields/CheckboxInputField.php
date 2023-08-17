<?php

namespace Nerbiz\WordClass\InputFields;

use Nerbiz\WordClass\Assets\Assets;
use Nerbiz\WordClass\Helpers;
use Nerbiz\WordClass\Init;

class CheckboxInputField extends AbstractInputField
{
    /**
     * Whether the script for checkboxes is added
     * @var bool
     */
    protected static bool $scriptsAdded = false;

    /**
     * The value when the checkbox is checked
     * @var string
     */
    protected string $onValue;

    /**
     * The value when the checkbox is unchecked
     * @var string
     */
    protected string $offValue;

    public function __construct(string $name, string $label, string $onValue = '1', string $offValue = '0')
    {
        $this->onValue = $onValue;
        $this->offValue = $offValue;

        // Add the required scripts (once)
        if (! static::$scriptsAdded) {
            (new Assets())->addAdminJs(
                Helpers::withPrefix('checkboxes', '-'),
                Init::getPackageUri('includes/js/checkboxes.js')
            );

            static::$scriptsAdded = true;
        }

        parent::__construct($name, $label);
    }

    /**
     * {@inheritdoc}
     */
    protected function renderField(): string
    {
        return sprintf(
            '<input type="hidden" name="%s">
            <input type="checkbox" class="wordclass-checkbox" data-on-value="%s" data-off-value="%s" %s %s>',
            $this->getFullName(),
            $this->onValue,
            $this->offValue,
            checked($this->onValue, $this->getStoredValue(), false),
            $this->getAttributesString()
        );
    }
}
