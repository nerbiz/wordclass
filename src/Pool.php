<?php

namespace Nerbiz\WordClass;

use Exception;

class Pool
{
    /**
     * The contents of the pool, in [name => value] pairs
     * @var array
     */
    protected static $contents = [];

    /**
     * Add or overwrite an item in the pool
     * @param string $name
     * @param        $value
     * @return void
     */
    public static function set(string $name, $value): void
    {
        static::$contents[$name] = $value;
    }

    /**
     * See if an item exists in the pool
     * @param string $name
     * @return bool
     */
    public static function has(string $name): bool
    {
        return (isset(static::$contents[$name]));
    }

    /**
     * Get an item from the pool
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public static function get(string $name)
    {
        if (static::has($name)) {
            return static::$contents[$name];
        }

        throw new Exception(sprintf(
            "%s(): item '%s' not found in the pool",
            __METHOD__,
            $name
        ));
    }
}
