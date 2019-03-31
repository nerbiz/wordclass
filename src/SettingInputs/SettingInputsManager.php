<?php

namespace Nerbiz\Wordclass\SettingInputs;

class SettingInputsManager
{
    /**
     * Get an input object
     * @param array $arguments Arguments for the input object
     * @return AbstractSettingInput
     * @throws \Exception
     */
    public function getInput(array $arguments): AbstractSettingInput
    {
        $className = ucfirst(strtolower($arguments['type']));
        $fullClass = __NAMESPACE__ . '\\' . $className;

        if (! class_exists($fullClass)) {
            throw new \Exception(sprintf(
                "%s(): No class found for type '%s'",
                __METHOD__,
                is_object($arguments['type']) ? get_class($arguments['type']) : $arguments['type']
            ));
        }

        $input = new $fullClass($arguments);
        return $input;
    }
}
