<?php

namespace Nerbiz\WordClass\InputFields;

use Nerbiz\WordClass\Init;

abstract class AbstractInputField
{
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
        $description = trim($this->description);

        if ($description !== '') {
            return sprintf(
                '<p class="description">%s</p>',
                $description
            );
        }

        return '';
    }
}
