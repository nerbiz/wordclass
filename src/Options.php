<?php

namespace Nerbiz\WordClass;

class Options
{
    /**
     * Get an option, implicitly using a prefix
     * @param string     $name    The name of the option (without prefix)
     * @param mixed|null $default The value to use when the option is empty
     * @return mixed
     */
    public static function get(string $name, mixed $default = null): mixed
    {
        $optionName = Init::getPrefix() . '_' . $name;
        return get_option($optionName, $default);
    }

    /**
     * Set an option, prefix will be added to the name
     * @param string $name The name of the option (without prefix)
     * @param mixed  $value
     * @return void
     */
    public static function set(string $name, mixed $value): void
    {
        $optionName = Init::getPrefix() . '_' . $name;
        update_option($optionName, $value);
    }

    /**
     * Delete an option, implicitly using a prefix
     * @param string $name The name of the option (without prefix)
     * @return void
     */
    public static function delete(string $name): void
    {
        $optionName = Init::getPrefix() . '_' . $name;
        delete_option($optionName);
    }
}
