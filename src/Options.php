<?php

namespace Nerbiz\WordClass;

use Closure;

class Options
{
    /**
     * Check whether an option exists in the database
     * @param string $name The name of the option (without prefix)
     * @return bool
     */
    public static function exists(string $name): bool
    {
        $optionName = Helpers::withPrefix($name);
        return (get_option($optionName) !== false);
    }

    /**
     * Get an option, implicitly using a prefix
     * @param string     $name    The name of the option (without prefix)
     * @param mixed|null $default The value to use when the option is empty, or doesn't exist
     * @return mixed
     */
    public static function get(string $name, mixed $default = null): mixed
    {
        $optionName = Helpers::withPrefix($name);
        $value = get_option($optionName);

        return ($value === false || $value === '')
            ? $default
            : $value;
    }

    /**
     * Set an option, prefix will be added to the name
     * @param string $name The name of the option (without prefix)
     * @param mixed  $value
     * @return void
     */
    public static function set(string $name, mixed $value): void
    {
        $optionName = Helpers::withPrefix($name);
        update_option($optionName, $value);
    }

    /**
     * Delete an option, implicitly using a prefix
     * @param string $name The name of the option (without prefix)
     * @return void
     */
    public static function delete(string $name): void
    {
        $optionName = Helpers::withPrefix($name);
        delete_option($optionName);
    }

    /**
     * Adjust an option value before it's stored
     * @param string  $name
     * @param Closure $callback Parameters are:
     *   - mixed $newValue
     *   - mixed $oldValue
     *   - string $optionName
     * @param int     $priority
     * @return void
     */
    public static function beforeSave(string $name, Closure $callback, int $priority = 10): void
    {
        $filterName = sprintf('pre_update_option_%s', Helpers::withPrefix($name));

        add_filter($filterName, $callback, $priority, 3);
    }

    /**
     * Adjust an option value after it's retrieved
     * @param string  $name
     * @param Closure $callback Parameters are:
     *   - mixed $value
     *   - string $optionName
     * @param int     $priority
     * @return void
     */
    public static function afterGet(string $name, Closure $callback, int $priority = 10): void
    {
        $filterName = sprintf('option_%s', Helpers::withPrefix($name));

        add_filter($filterName, $callback, $priority, 2);
    }
}
