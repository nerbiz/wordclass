<?php

namespace Nerbiz\WordClass;

class Utilities
{
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
        // Replace any non-alphanumeric character with a hyphen
        $slug = preg_replace('/[^a-z\d]/', '-', $slug);
        // Remove consecutive hyphens
        $slug = preg_replace('/-{2,}/', '-', $slug);
        // Remove leading and trailing hyphens
        return trim($slug, '-');
    }
}
