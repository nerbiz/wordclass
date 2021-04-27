<?php

namespace Nerbiz\WordClass;

class Options
{
    /**
     * Get an option, implicitly using a prefix
     * @param string $name    The name of the option (without prefix)
     * @param mixed  $default The value to use when the option is empty
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        $optionName = Init::getPrefix() . '_' . $name;
        $value = trim(get_option($optionName));

        if ($value === '') {
            return $default;
        }

        return $value;
    }

    /**
     * Set an option, implicitly using a prefix
     * @param  string $name The name of the option (without prefix)
     * @param  mixed  $value
     * @return self
     */
    public function set(string $name, $value): self
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
