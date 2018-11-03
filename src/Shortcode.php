<?php

namespace Nerbiz\Wordclass;

class Shortcode
{
    /**
     * The shortcode tag
     * @var string
     */
    protected $tag;

    /**
     * Whether the shortcode is enclosing
     * Enclosing: [code option="value"]...[/code]
     * Not enclosing: [code option="value"]
     * @var bool
     */
    protected $enclosing = false;

    /**
     * Default values for the shortcode attributes
     * Also used as a list of all supported attributes
     * @var array
     */
    protected $defaultValues = [];

    /**
     * The shortcode handler
     * @var callable
     */
    protected $handler;

    /**
     * The text on the option in the editor toolbar dropdown
     * @var string
     */
    protected $optionLabel;

    /**
     * For converting type names, from normal type name to TinyMCE type name
     * @var array
     */
    protected $inputTypeMap = [
        'text'     => 'textbox',
        'dropdown' => 'listbox',
    ];

    /**
     * Input defintions for a TinyMCE modal dialog
     * @var array[array]
     */
    protected $modalDialogInputs = [];

    /**
     * @param  string $tag
     * @return self
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @param  bool $enclosing
     * @return self
     */
    public function setEnclosing($enclosing)
    {
        $this->enclosing = $enclosing;

        return $this;
    }

    /**
     * @param  callable $handler
     * @return self
     */
    public function setHandler($handler)
    {
        if (is_callable($handler)) {
            $this->handler = $handler;
        }

        return $this;
    }

    /**
     * @param  string $optionLabel
     * @return self
     */
    public function setOptionLabel($optionLabel)
    {
        $this->optionLabel = $optionLabel;

        return $this;
    }

    /**
     * @return array
     */
    public function getModalDialogProperties()
    {
        return [
            'tag'         => $this->tag,
            'enclosing'   => $this->enclosing,
            'optionLabel' => $this->optionLabel,
            'inputs'      => $this->modalDialogInputs,
        ];
    }

    /**
     * Add a shortcode attribute
     * @param  string      $name            The name of the parameter
     * @param  string|null $defaultValue    The default value of the parameter
     * @param  array|null  $inputProperties Properties for an input field (in modal dialog) for the attribute
     * Input properties:
     * type: (optional) the type of input (default is 'text')
     * label: (optional) the label for the input field
     * tooltip: (optional) tooltip text to show when hovering the input element
     * For type 'dropdown':
     *   values: an array of choosable values
     * For type 'checkbox':
     *   text: (optional) the text to show next to the checkbox
     *   checked: (optional) to show the checkbox checked by default (default is false)
     * @return self
     */
    public function addAttribute($name, $defaultValue = null, array $inputProperties = null)
    {
        // Add the default value, also used for the list of supported attributes
        $this->defaultValues[$name] = $defaultValue;

        // Create the TinyMCE input definition, if needed
        if ($inputProperties !== null && is_array($inputProperties) && count($inputProperties) > 0) {
            $tinyMceInput = array_replace([
                'name'  => $name,
                'value' => $defaultValue,
                'type'  => 'text',
                'label' => $name,
                // Dropdown needs 'values' property (array of selectable options)
                // Checkbox has optional 'checked' property
            ], $inputProperties);

            // Convert the type name if needed
            if (isset($this->inputTypeMap[$tinyMceInput['type']])) {
                $tinyMceInput['type'] = $this->inputTypeMap[$tinyMceInput['type']];
            }

            $this->modalDialogInputs[] = $tinyMceInput;
        }

        return $this;
    }

    /**
     * Add a label for the modal dialog
     * This doesn't add anything to the shortcode
     * @param  string $text
     * @return self
     */
    public function addModalText($text = null)
    {
        // An empty label creates an empty line
        if (trim($text) === null) {
            $text = html_entity_decode('&nbsp;');
        }

        $this->modalDialogInputs[] = [
            'type' => 'label',
            'text' => $text,
        ];

        return $this;
    }

    /**
     * Add an empty line to the modal dialog
     * @return self
     */
    public function addModalEmptyLine()
    {
        return $this->addModalText(null);
    }

    /**
     * Create the shortcode
     * @return self
     */
    public function create()
    {
        add_shortcode($this->tag, function ($attributes, $content = null) {
            $attributes = shortcode_atts($this->defaultValues, $attributes, $this->tag);
            return call_user_func($this->handler, $attributes, $content);
        });

        return $this;
    }
}
