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
     * @param string      $name
     * @param string      $label
     * @param string|null $description
     * @param array       $values
     */
    public function __construct(
        string $name,
        string $label,
        ?string $description,
        array $values
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
                $this->getFullName(),
                $value,
                checked($value, esc_attr(get_option($this->getFullName())), false),
                $label
            );
        }

        return $output;
    }
}
