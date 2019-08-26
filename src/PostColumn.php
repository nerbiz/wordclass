<?php

namespace Nerbiz\Wordclass;

class PostColumn implements WordclassInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $orderBy = 'date';

    /**
     * @var string|null
     */
    protected $after = null;

    /**
     * @var callable
     */
    protected $renderFunction = null;

    /**
     * @param string $name  The identifier of the column
     * @param string $label The label in the column head
     */
    public function __construct(string $name, string $label)
    {
        $this->name = $name;
        $this->label = $label;
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
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getOrderBy(): string
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
