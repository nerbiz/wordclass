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
    protected bool $fullWidth = false;

    /**
     * The prefix for the input name
     * @var string
     */
    protected string $namePrefix = '';

    /**
     * @param string      $name The name/id of the input field
     * @param string      $label The label of the input field
     * @param string|null $description The optional description below the input field
     */
    public function __construct(
        protected string $name,
        protected string $label,
        protected ?string $description = null
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
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isFullWidth(): bool
    {
        return $this->fullWidth;
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
     * @return string
     */
    public function getNamePrefix(): string
    {
        return $this->namePrefix;
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
     * Get a prefixed input name
     * @return string
     */
    public function getFullName(): string
    {
        return sprintf(
            '%s_%s%s',
            Init::getPrefix(),
            $this->getNamePrefix(),
            $this->getName()
        );
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
}
