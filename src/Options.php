<?php

namespace Nerbiz\WordClass;

class Options
{
    /**
     * Get an option, implicitly using a prefix
     * @param string     $name The name of the option (without prefix)
     * @param mixed|null $default The value to use when the option is empty
     * @return mixed
     */
    public function get(string $name, mixed $default = null): mixed
    {
        $optionName = Init::getPrefix() . '_' . $name;
        return get_option($optionName, $default);
    }

    /**
     * Set an option, prefix will be added to the name
     * @param  string $name The name of the option (without prefix)
     * @param  mixed  $value
     * @return self
     */
    public function set(string $name, mixed $value): self
    {
        $optionName = Init::getPrefix() . '_' . $name;
        update_option($optionName, $value);

        return $this;
    }

    /**
     * Delete an option, implicitly using a prefix
     * @param  string $name The name of the option (without prefix)
     * @return self
     */
    public function delete(string $name): self
    {
        $optionName = Init::getPrefix() . '_' . $name;
        delete_option($optionName);

        return $this;
    }
}
