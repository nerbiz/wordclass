<?php

namespace Nerbiz\WordClass;

class Utilities
{
    /**
     * Convert a string to lowercase slug format
     * @param string $string
     * @param string $separator The separator between words
     * @return string
     */
    public static function createSlug(string $string, string $separator = '-'): string
    {
        $slug = html_entity_decode($string);
        $slug = remove_accents($slug);
        $slug = strtolower($slug);
        // Replace any non-alphanumeric character with a separator
        $slug = preg_replace('/[^a-z\d]/', $separator, $slug);
        // Remove consecutive separators
        $slug = preg_replace('/' . $separator . '{2,}/', $separator, $slug);
        // Remove leading and trailing separators
        return trim($slug, $separator);
    }
}
