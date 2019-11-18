<?php

namespace Nerbiz\Wordclass;

class PostColumn
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string|null
     */
    protected $orderBy = null;

    /**
     * @var string|null
     */
    protected $after = null;

    /**
     * @var callable
     */
    protected $renderFunction = null;

    /**
     * @param string $id    The identifier of the column
     * @param string $label The label in the column head
     */
    public function __construct(string $id, string $label)
    {
        $this->id = $id;
        $this->label = $label;
    }

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
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string|null
     */
    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     * @return self
     */
    public function setOrderBy(string $orderBy): self
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAfter(): ?string
    {
        return $this->after;
    }

    /**
     * @param string $after
     * @return self
     */
    public function setAfter(string $after): self
    {
        $this->after = $after;

        return $this;
    }

    /**
     * @return callable
     */
    public function getRenderFunction(): callable
    {
        return $this->renderFunction;
    }

    /**
     * @param callable $renderFunction
     * @return self
     */
    public function setRenderFunction(callable $renderFunction): self
    {
        $this->renderFunction = $renderFunction;

        return $this;
    }
}
