<?php

namespace Nerbiz\WordClass\InputFields;

class SelectInputField extends AbstractInputField
{
    /**
     * The select values, in value:label pairs
     * Can also be an array with a groupName:[value:label, value:label] structure
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
        $output = sprintf('<select name="%s">', $this->getPrefixedName());

        foreach ($this->values as $key => $value) {
            if (is_string($value)) {
                // Add an option, if it's a normal value:label pair
                $output .= $this->createOptionField($key, $value);
            } elseif (is_array($value)) {
                // Create an options group, if it's an array
                $output .= sprintf('<optgroup label="%s">', $key);
                foreach ($value as $optionValue => $optionLabel) {
                    $output .= $this->createOptionField($optionValue, $optionLabel);
                }
                $output .= '</optgroup>';
            }
        }

        $output .= '</select>';
        return $output;
    }

    /**
     * Create an HTML option element based on a value and label
     * @param string $value
     * @param string $label
     * @return string
     */
    protected function createOptionField(string $value, string $label): string
    {
        return sprintf(
            '<option value="%s" %s>%s</option>',
            $value,
            selected($value, get_option($this->getPrefixedName()), false),
            $label
        );
    }
}
