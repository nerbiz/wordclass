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
    public static function obscureEmailLink(string $address, ?string $name = null): string
    {
        if ($name !== null) {
            return sprintf(
                '<a href="%1$s%2$s <%3$s>">%3$s</a>',
                static::stringToNumericHtmlEntities('mailto:'),
                static::stringToNumericHtmlEntities($name),
                static::stringToNumericHtmlEntities($address)
            );
        }

        return sprintf(
            '<a href="%1$s%2$s">%2$s</a>',
            static::stringToNumericHtmlEntities('mailto:'),
            static::stringToNumericHtmlEntities($address)
        );
    }

    /**
     * Obscure a phone number to make it harder for bots to see
     * @param  string $number
     * @return string
     */
    public static function obscurePhoneLink(string $number): string
    {
        return sprintf(
            '<a href="%1$s%2$s">%2$s</a>',
            static::stringToNumericHtmlEntities('tel:'),
            static::stringToNumericHtmlEntities($number)
        );
    }

    /**
     * Convert a string to numeric HTML entities
     * Example: 'test' becomes '&#116;&#101;&#115;&#116;'
     * @param string $string
     * @return string
     */
    public static function stringToNumericHtmlEntities(string $string): string
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
    public static function createSlug(string $string): string
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
