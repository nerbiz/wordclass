<?php

namespace Nerbiz\Wordclass\SettingInputs;

abstract class AbstractSettingInput
{
    /**
     * The arguments for the input field
     * @var array
     */
    protected $arguments;

    /**
     * @param array $arguments
     */
    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * Render the input element
     * @return string
     */
    abstract public function render(): string;
}
