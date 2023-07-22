<?php

namespace Nerbiz\WordClass\InputFields;

class RadioButtonsInputField extends AbstractInputField
{
    /**
     * The radio button values, in value:label pairs
     * @var array
     */
    protected array $values = [];

    /**
     * @param string $name
     * @param string $label
     * @param array  $values
     */
    public function __construct(string $name, string $label, array $values) {
        $this->values = $values;

        parent::__construct($name, $label);
    }

    /**
     * {@inheritdoc}
     */
    protected function renderField(): string
    {
        $output = '';

        foreach ($this->values as $value => $label) {
            $output .= sprintf(
                '<p><label>
                    <input type="radio" name="%s" value="%s" %s>
                    %s
                </label></p>',
                $this->getFullName(),
                $value,
                checked($value, $this->getStoredValue(), false),
                $label
            );
        }

        return $output;
    }
}
