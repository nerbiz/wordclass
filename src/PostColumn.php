<?php

namespace Nerbiz\WordClass;

use Closure;

class PostColumn
{
    /**
     * @var string|null
     */
    protected ?string $orderBy = null;

    /**
     * @var string|null
     */
    protected ?string $after = null;

    /**
     * @var Closure|null
     */
    protected Closure|null $renderFunction = null;

    /**
     * @param string $name  The identifier of the column
     * @param string $label The label in the column head
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
     * @return Closure|null
     */
    public function getRenderFunction(): ?Closure
    {
        return $this->renderFunction;
    }

    /**
     * @param Closure $renderFunction
     * @return self
     */
    public function setRenderFunction(Closure $renderFunction): self
    {
        $this->renderFunction = $renderFunction;

        return $this;
    }
}
