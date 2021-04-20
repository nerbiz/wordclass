<?php

namespace Nerbiz\WordClass;

use Nerbiz\WordClass\InputFields\AbstractInputField;

class SettingsPageSection
{
    /**
     * The unique ID of the section
     * @var string
     */
    protected $id;

    /**
     * The section title
     * @var string
     */
    protected $title;

    /**
     * The section subtitle
     * @var string|null
     */
    protected $subtitle = null;

    /**
     * The fields of the section
     * @var AbstractInputField[]
     */
    protected $fields = [];

    /**
     * @param string               $id
     * @param string               $title
     * @param string|null          $subtitle
     * @param AbstractInputField[] $fields
     */
    public function __construct(string $id, string $title, ?string $subtitle = null, array $fields)
    {
        $this->setId($id);
        $this->setTitle($title);
        $this->setSubtitle($subtitle);
        $this->setFields($fields);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    /**
     * @param string|null $subtitle
     * @return self
     */
    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;
        return $this;
    }

    /**
     * @return AbstractInputField[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param AbstractInputField[] $fields
     * @return self
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;
        return $this;
    }
}
