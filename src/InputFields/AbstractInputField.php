<?php

namespace Nerbiz\WordClass\InputFields;

use Nerbiz\WordClass\Helpers;
use Nerbiz\WordClass\Init;

abstract class AbstractInputField
{
    /**
     * Attributes of the input field, as key/value pairs
     * Boolean attributes don't need a value, only the name will suffice
     * @var array
     */
    protected array $attributes = [];

    /**
     * The description below the input field
     * @var string
     */
    protected string $description = '';

    /**
     * Whether the input field spans the full width,
     * instead of having label and field separately
     * @var bool
     */
    protected bool $fullWidth = false;

    /**
     * The prefix for the input name
     * @var string
     */
    protected string $namePrefix = '';

    /**
     * @param string      $name The name/id of the input field
     * @param string      $label The label of the input field
     */
    public function __construct(
        protected string $name,
        protected string $label
    ) {}

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param array $attributes
     * @return self
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Convert all attributes to a single string
     * @return string
     */
    public function getAttributesString(): string
    {
        $attributes = [];

        foreach ($this->getAttributes() as $name => $value) {
            // Boolean attributes like 'required' and 'disabled'
            if (is_numeric($name)) {
                $attributes[] = $value;
                continue;
            }

            // Convert true/false to strings, for attributes like 'draggable' and 'contenteditable'
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            $attributes[] = sprintf('%s="%s"', $name, $value);
        }

        return implode(' ', $attributes);
    }

    /**
     * @param string $description
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param bool $fullWidth
     * @return self
     */
    public function setFullWidth(bool $fullWidth): self
    {
        $this->fullWidth = $fullWidth;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFullWidth(): bool
    {
        return $this->fullWidth;
    }

    /**
     * @param string $namePrefix
     * @return self
     */
    public function setNamePrefix(string $namePrefix): self
    {
        $this->namePrefix = $namePrefix . '_';

        return $this;
    }

    /**
     * @return string
     */
    public function getNamePrefix(): string
    {
        return $this->namePrefix;
    }

    /**
     * Get a prefixed input name
     * @return string
     */
    public function getFullName(): string
    {
        return Helpers::withPrefix(sprintf(
            '%s%s',
            $this->getNamePrefix(),
            $this->getName())
        );
    }

    public function getStoredValue(): string
    {
        return esc_attr(get_option($this->getFullName()));
    }

    /**
     * Render the input field
     * @return string
     */
    public function render(): string
    {
        return sprintf(
            '%s%s%s',
            $this->prependRender(),
            $this->renderField(),
            $this->appendRender()
        );
    }

    /**
     * HTML to be prepended to the input field rendering
     * @return string
     */
    protected function prependRender(): string
    {
        return '';
    }

    /**
     * Render the input field itself
     * @return string
     */
    abstract protected function renderField(): string;

    /**
     * HTML to be appended to the input field rendering
     * @return string
     */
    protected function appendRender(): string
    {
        $description = trim($this->description);

        return ($description !== '')
            ? sprintf('<p class="description">%s</p>', $description)
            : '';
    }
}
