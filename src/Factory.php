<?php

namespace Nerbiz\Wordclass;

use ReflectionClass;
use Exception;

class Factory
{
    /**
     * Create and return a new instance
     * @param  string $classname The name of the class to initiate
     * @param  array  $arguments Constructor arguments for the class
     * @return object
     * @throws Exception If the class is not found
     */
    public function make($classname, $arguments = [])
    {
        // Prepend the namespace to the classname if needed
        if (strpos($classname, __NAMESPACE__) === false) {
            $fullyQualifiedClassname = __NAMESPACE__ . '\\' . $classname;
        } else {
            $fullyQualifiedClassname = $classname;
        }

        if (class_exists($fullyQualifiedClassname)) {
            $reflection = new ReflectionClass($fullyQualifiedClassname);
            return $reflection->newInstanceArgs($arguments);
        }

        throw new Exception(sprintf(
            "%s(): the class '%s' is not found",
            __METHOD__,
            $fullyQualifiedClassname
        ));
    }
}
