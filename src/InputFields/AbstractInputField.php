<?php

namespace Nerbiz\WordClass\InputFields;

use Nerbiz\WordClass\Init;

abstract class AbstractInputField
{
    /**
     * Whether the input field spans the full width,
     * instead of having label and field separately
     * @var bool
     */
    protected $fullWidth = false;

    /**
     * The name/id of the input field
     * @var string
     */
    protected $name;

    /**
     * The label of the input field
     * @var string
     */
    protected $label;

    /**
     * The optional description below the input field
     * @var string|null
     */
    protected $description;

    /**
     * The prefix for the input name
     * @var string
     */
    protected $namePrefix = '';

    /**
     * @param string      $name
     * @param string      $label
     * @param string|null $description
     */
    public function __construct(string $name, string $label, ?string $description = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->description = $description;
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
     * @param string $namePrefix
     * @return self
     */
    public function setNamePrefix(string $namePrefix): self
    {
        $this->namePrefix = $namePrefix . '_';

        return $this;
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
        if ($this->description !== null) {
            return sprintf(
                '<p class="description">%s</p>',
                $this->description
            );
        }

        return '';
    }

    /**
     * @return bool
     */
    public function isFullWidth(): bool
    {
        return $this->fullWidth;
    }

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
    public function getNamePrefix(): string
    {
        return $this->namePrefix;
    }

    /**
     * Get a prefixed input name
     * @return string
     */
    public function getPrefixedName(): string
    {
        // Return a longer name, if a prefix exists
        return sprintf(
            '%s_%s%s',
            Init::getPrefix(),
            $this->getNamePrefix(),
            $this->getName()
        );
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
