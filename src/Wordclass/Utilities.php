<?php

namespace Wordclass;

class Utilities {
    /**
     * Convert an email address to HTML character codes
     * @param  String       $address
     * @param  String|null  $name     (Optional) Include the recipient name in the email link
     * @return String
     */
    public static function encryptEmailLink($address, $name=null) {
        $encryptedMailTo = static::utf8ToHtmlEntities('mailto:');
        $encryptedAddress = static::utf8ToHtmlEntities($address);
        if($name)
            $encryptedName = static::utf8ToHtmlEntities($name);

        if($name)
            return '<a href="'.$encryptedMailTo.$encryptedName.' <'.$encryptedAddress.'>">'.$encryptedAddress.'</a>';
        else
            return '<a href="'.$encryptedMailTo.$encryptedAddress.'">'.$encryptedAddress.'</a>';
    }



    /**
     * Convert a phone number to HTML character codes
     * @param  String  $number
     * @return String
     */
    public static function encryptPhoneLink($number) {
        $encryptedTel = static::utf8ToHtmlEntities('tel:');
        $encryptedNumber = static::utf8ToHtmlEntities($number);

        // if(Device::isMobile())
        //     return '<a href="'.$encryptedTel.$encryptedNumber.'">'.$encryptedNumber.'</a>';
        // else
            return $encryptedNumber;
    }



    /**
     * Create a random ID
     * @param  Integer  $length   The length of the ID
     * @param  Boolean  $lower    Whether or not to use lowercase characters
     * @param  Boolean  $upper    Whether or not to use uppercase characters
     * @param  Boolean  $numbers  Whether or not to use numbers
     * @param  Boolean  $special  Whether or not to use special characters
     * @return String
     */
    public static function randomId($length=20, $lower=true, $upper=true, $numbers=true, $special=false) {
        $requirementSum = (int) $lower + (int) $upper + (int) $numbers + (int) $special;

        // The length has to be at least 1 character, and at least 1 requirement is needed
        // Also, the length needs to be at least the amount of requirements
        if($length < 1  ||  $requirementSum == 0  ||  $length < $requirementSum)
            return '';

        // The character pool to choose from
        $characterPool = [
            'lower' => 'abcdefghijklmnopqrstuvwxyz',
            'upper' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'numbers' => '0123456789',
            'special' => '!@#$%^&*()'
        ];

        $stringCheckOk = (int) $lower.(int) $upper.(int) $numbers.(int) $special;

        // Generate a new ID, until a valid one is generated
        do {
            $randomId = '';
            $stringChecker = '';

            for($i=0;  $i<$length;  $i++) {
                // First get the type of characters (random choice), then get a random character from it
                // If all characters were in 1 string, letters would occur the most
                $characters = $characterPool[array_rand($characterPool)];
                $randomId .= $characters[rand(0, (strlen($characters) - 1))];
            }

            // Create a checker that needs to match the requirements
            // (example: '1110' = lowercase, uppercase and numbers, but no special characters)
            $stringChecker .= (int) preg_match('~[a-z]~', $randomId);
            $stringChecker .= (int) preg_match('~[A-Z]~', $randomId);
            $stringChecker .= (int) preg_match('~\d~', $randomId);
            $stringChecker .= (int) preg_match('~['.preg_quote($characterPool['special']).']~', $randomId);
        } while($stringChecker != $stringCheckOk);

        return $randomId;
    }



    /**
     * Ensure exactly 1 forward slash, appended to a string
     * @param  String  $string
     * @return String
     */
    public static function ensureEndSlash($string) {
        // Remove all currently appended forward slashes, and append 1
        return rtrim($string, '/').'/';
    }



    /**
     * Format a decimal number with a comma as decimal separator
     * @param  String|Float  $number
     * @param  Integer       $decimals   The amount of decimals
     * @param  String        $thousands  The thousands separator
     * @return String
     */
    public static function decimalComma($number, $decimals=2, $thousands='') {
        $number = floatval(str_replace(',', '.', $number));
        return number_format($number, $decimals, ',', $thousands);
    }



    /**
     * Format a decimal number with a period as decimal separator
     * @param  String|Float  $number
     * @param  Integer       $decimals  The amount of decimals
     * @param  String        $thousands  The thousands separator
     * @return String
     */
    public static function decimalPeriod($number, $decimals=2, $thousands='') {
        $number = floatval(str_replace(',', '.', $number));
        return number_format($number, $decimals, '.', $thousands);
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
     * Keep a number (integer/float) within boundaries
     * @param  Integer/Float  $value
     * @param  Integer/Float  $min    The minimum boundary
     * @param  Integer/Float  $max    The maximum boundary
     * @return Integer/Float
     */
    public static function withinBoundaries($value, $min, $max) {
        if($value < $min)
            $value = $min;
        if($value > $max)
            $value = $max;

        return $value;
    }



    /**
     * Remove the last <br> from a string, and optionally newline character(s) as well
     * @param  String   $string
     * @param  Boolean  $newlines  Whether to remove newline characters or not
     * @return String
     */
    public static function removeLastBr($string, $newlines=false) {
        $replace = ($newlines) ? '' : '$1';
        return preg_replace('~<br ?/?>('.PHP_EOL.'*)$~', $replace, $string);
    }



    /**
     * Get the depth of a (multidimensional) array
     * @param  Array  $array
     * @return Integer
     */
    public static function getArrayDepth($array) {
        $arrayDepth = 1;

        if(is_array($array)) {
            foreach($array as $value) {
                if(is_array($value)) {
                    $depth = static::getArrayDepth($value) + 1;

                    if($depth > $arrayDepth)
                        $arrayDepth = $depth;
                }
            }
        }

        return $arrayDepth;
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
