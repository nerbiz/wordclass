<?php

namespace Nerbiz\WordClass;

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
}
