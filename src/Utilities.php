<?php

namespace Nerbiz\Wordclass;

class Utilities
{
    /**
     * Obscure an email to make it harder for bots to see
     * @param  string      $address
     * @param  string|null $name    The name in the email link
     * @return string
     */
    public function obscureEmailLink($address, $name = null)
    {
        if ($name !== null) {
            return sprintf(
                '<a href="%1$s%2$s <%3$s>">%3$s</a>',
                $this->toNumericHtmlEntity('mailto:'),
                $this->toNumericHtmlEntity($name),
                $this->toNumericHtmlEntity($address)
            );
        }

        return sprintf(
            '<a href="%1$s%2$s">%2$s</a>',
            $this->toNumericHtmlEntity('mailto:'),
            $this->toNumericHtmlEntity($address)
        );
    }

    /**
     * Obscure a phone number to make it harder for bots to see
     * @param  string $number
     * @return string
     */
    public function obscurePhoneLink($number)
    {
        return sprintf(
            '<a href="%1$s%2$s">%2$s</a>',
            $this->toNumericHtmlEntity('tel:'),
            $this->toNumericHtmlEntity($number)
        );
    }

    /**
     * Convert a string to numeric HTML entities
     * Example: 'test' becomes '&#116;&#101;&#115;&#116;'
     * @param  $string
     * @return string
     */
    public function toNumericHtmlEntity($string)
    {
        $output = '';
        foreach (str_split($string) as $character) {
            $output .= '&#' . ord($character) . ';';
        }

        return $output;
    }

    /**
     * Convert a string to lowercase slug format
     * @param  string $string
     * @return string
     */
    public function createSlug($string)
    {
        $slug = html_entity_decode($string);
        $slug = remove_accents($slug);
        $slug = strtolower($slug);
        // Replace any non-alphanumeric character to a hyphen
        $slug = preg_replace('~[^a-z\d]~', '-', $slug);
        // Remove consecutive hyphens
        $slug = preg_replace('~-{2,}~', '-', $slug);
        // Remove leading and trailing hyphens
        $slug = trim($slug, '-');

        return $slug;
    }
}
