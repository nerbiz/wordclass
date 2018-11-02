<?php

namespace Nerbiz\Wordclass;

use ReflectionClass;

class Factory
{
    /**
     * Create and return a new instance
     * @param  string $classname The name of the class to initiate
     * @param  array  $arguments Constructor arguments, in name:value pairs
     * @return object
     * @throws \ReflectionException If the class is not found
     * @throws \Exception If no parameter value is given, and there is no default
     */
    public static function make($classname, $arguments = [])
    {
        // Prepend the namespace to the classname if needed
        if (strpos($classname, __NAMESPACE__) === false) {
            $fullyQualifiedClassname = __NAMESPACE__ . '\\' . $classname;
        } else {
            $fullyQualifiedClassname = $classname;
        }

        $reflection = new ReflectionClass($fullyQualifiedClassname);
        $constructorArguments = [];

        // Create the constructor arguments in the right order
        if (($constructor = $reflection->getConstructor()) !== null) {
            foreach ($constructor->getParameters() as $parameter) {
                // Set a provided value if it exists
                if (array_key_exists($parameter->getName(), $arguments)) {
                    $constructorArguments[] = $arguments[$parameter->getName()];
                }

                // Or use the default value
                else {
                    if ($parameter->isOptional()) {
                        $constructorArguments[] = $parameter->getDefaultValue();
                    } else {
                        throw new \Exception(sprintf(
                            "%s(): no value provided for required parameter '%s'",
                            __METHOD__,
                            $parameter->getName()
                        ));
                    }
                }
            }
        }

        return $reflection->newInstanceArgs($constructorArguments);
    }
}
