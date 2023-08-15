<?php

namespace Nerbiz\WordClass;

use Nerbiz\WordClass\InputFields\AbstractInputField;

class SettingsPageSection
{
    /**
     * @param string               $id     The unique ID of the section
     * @param string               $title  The section title
     * @param AbstractInputField[] $fields The input fields of the section
     */
    public function __construct(
        protected string $id,
        protected string $title,
        protected array $fields
    ) {}

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return AbstractInputField[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
