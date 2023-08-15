<?php

namespace Nerbiz\WordClass;

class Pool
{
    /**
     * The contents of the pool
     * @var array
     */
    protected static array $contents = [];

    /**
     * Add or overwrite an item in the pool
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public static function set(string $key, mixed $value): void
    {
        static::$contents[$key] = $value;
    }

    /**
     * See if an item exists in the pool
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return array_key_exists($key, static::$contents);
    }

    /**
     * Get an item from the pool
     * @param string $key
     * @param mixed  $default The value to use when the option is empty
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if (static::has($key)) {
            return static::$contents[$key];
        }

        return $default;
    }

    /**
     * Get all the items from the pool
     * @return array
     */
    public static function getAll(): array
    {
        return static::$contents;
    }
}
