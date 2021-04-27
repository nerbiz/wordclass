<?php

namespace Nerbiz\WordClass\InputFields;

class RadioButtonsInputField extends AbstractInputField
{
    /**
     * The radio button values, in value:label pairs
     * @var array
     */
    protected $values = [];

    /**
     * @param string      $name
     * @param string      $label
     * @param array       $values
     * @param string|null $description
     */
    public function __construct(
        string $name,
        string $label,
        array $values,
        ?string $description = null
    ) {
        $this->values = $values;

        parent::__construct($name, $label, $description);
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
                $this->getPrefixedName(),
                $value,
                checked($value, esc_attr(get_option($this->getPrefixedName())), false),
                $label
            );
        }

        return $output;
    }
}
