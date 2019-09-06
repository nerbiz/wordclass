<?php

namespace Nerbiz\Wordclass\InputFields;

use Nerbiz\Wordclass\Init;

abstract class AbstractInputField
{
    /**
     * The name/id of the input field
     * @var string
     */
    protected $name;

    /**
     * The title of the input field
     * @var string
     */
    protected $title;

    /**
     * The optional description below the input field
     * @var string|null
     */
    protected $description;

    /**
     * @param string      $name
     * @param string      $title
     * @param string|null $description
     */
    public function __construct(string $name, string $title, ?string $description = null)
    {
        $this->name = (new Init())->getPrefix() . '_' . $name;
        $this->title = $title;
        $this->description = $description;
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
