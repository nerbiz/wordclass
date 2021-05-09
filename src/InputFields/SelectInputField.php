<?php

namespace Nerbiz\WordClass\InputFields;

class SelectInputField extends AbstractInputField
{
    /**
     * The select values, in value:label pairs
     * Can also be an array with a groupName:[value:label, value:label] structure
     * @var array
     */
    protected $values = [];

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
        $output = sprintf('<select name="%s">', $this->getName());

        foreach ($this->values as $left => $right) {
            if (is_string($right)) {
                // Add an option, if it's a normal value:label pair
                $output .= sprintf('<option value="%s">%s</option>', $left, $right);
            } elseif (is_array($right)) {
                // Create an options group, if it's an array
                $output .= sprintf('<optgroup label="%s">', $left);
                foreach ($right as $value => $label) {
                    $output .= sprintf('<option value="%s">%s</option>', $value, $label);
                }
                $output .= '</optgroup>';
            }
        }

        $output .= '</select>';
        return $output;
    }
}
