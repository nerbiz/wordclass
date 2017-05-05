<?php

namespace Wordclass;

class Utilities {
    /**
     * Convert an email address to HTML character codes
     * @param  String       $address
     * @param  String|null  $name     (Optional) Include the recipient name in the email link
     * @return String
     */
    public static function obscureEmailLink($address, $name=null) {
        $obscuredMailTo = static::utf8ToHtmlEntities('mailto:');
        $obscuredAddress = static::utf8ToHtmlEntities($address);
        if($name)
            $obscuredName = static::utf8ToHtmlEntities($name);

        if($name)
            return '<a href="'.$obscuredMailTo.$obscuredName.' <'.$obscuredAddress.'>">'.$obscuredAddress.'</a>';
        else
            return '<a href="'.$obscuredMailTo.$obscuredAddress.'">'.$obscuredAddress.'</a>';
    }



    /**
     * Convert a phone number to HTML character codes
     * @param  String  $number
     * @return String
     */
    public static function obscurePhoneLink($number) {
        $obscuredTel = static::utf8ToHtmlEntities('tel:');
        $obscuredNumber = static::utf8ToHtmlEntities($number);

        // if(Device::isMobile())
        //     return '<a href="'.$obscuredTel.$obscuredNumber.'">'.$obscuredNumber.'</a>';
        // else
            return $obscuredNumber;
    }



    /**
     * Convert a string to lowercase slug format
     * @param  String  $string
     * @return String
     */
    public static function createSlug($string) {
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



    /**
     * Convert a string to binary
     * @param  String   $string
     * @param  Boolean  $array   Whether to return an array or string
     * @return Array|String
     */
    public static function stringToBinary($string, $array=false) {
        $string = (string) $string;
        $length = strlen($string);
        $result = array();

        for($i=0;  $i<$length;  $i++) {
            $decimal = ord($string[$i]);
            $binary = sprintf('%08d', base_convert($decimal, 10, 2));
            $result[] = $binary;
        }

        if($array)
            return $result;
        else
            return implode(' ', $result);
    }



    /**
     * Convert utf8 characters into #&000; format
     * @param  String   $string
     * @param  Boolean  $literal  Whether to return the HTML codes (true) or not (false)
     * @return String
     */
    public static function utf8ToHtmlEntities($string, $literal=false) {
        $string = (string) $string;
        $htmlEntities = array();

        $bytes = static::stringToBinary($string, true);

        while( ! empty($bytes)) {
            $byte = (string) $bytes[0];

            // Normal 7 bit character
            if($byte[0] === '0') {
                $htmlEntities[] = '&#'.base_convert($byte, 2, 10).';';
                array_shift($bytes);
            }

            // UTF-8 multibyte character
            else {
                // Count the bytes for this character
                $count = 0;
                while($byte[$count] !== '0')
                    $count++;

                // First byte
                $binary = substr($byte, $count);
                array_shift($bytes);

                // Followup bytes
                for($i=0;  $i<($count-1);  $i++)
                    $binary .= substr(array_shift($bytes), 2);

                $htmlEntities[] = '&#'.base_convert($binary, 2, 10).';';
            }
        }

        $htmlEntities = implode('', $htmlEntities);

        if($literal)
            return str_replace('&', '&amp;', $htmlEntities);
        else
            return $htmlEntities;
    }
}
