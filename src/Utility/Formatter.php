<?php

namespace ConstantNull\Backstubber\Utility;

class Formatter
{
    /**
     * Return text representation of array
     *
     * @param array $array input array
     * @param bool $braces add array braces to output
     * @return string
     */
    public static function formatArray(array $array, $braces = true)
    {
        $output = implode($array, "', '");

        if ($output) {
            $output = "'$output'";
        }

        if ($braces) {
            $output = "[$output]";
        }

        return $output;
    }

    /**
     * Prepares scalar value
     * (adds quotes to the ends of the string, replaces booleans with literal true|false)
     *
     * @param mixed $value scalar type value
     * @return string
     */
    public static function formatScalar($value)
    {
        if (is_string($value)) {
            $value = "'$value'";
        }

        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }

        return "$value";
    }

    /**
     * Generates stub ready variable line
     *
     * @param string $name variable name
     * @param mixed $value value of the variable
     * @return string
     */
    public static function formatVariable($name, $value)
    {
        $value = self::formatScalar($value);

        return "\$$name = $value;";
    }

    /**
     * Generates stub ready property
     *
     * @param string $keywords property keywords such as protected | public static
     * @param string $name property name
     * @param mixed $value value of the property
     * @return string
     */
    public static function formatProperty($keywords, $name, $value)
    {
        return $keywords . ' ' . self::formatVariable($name, $value);
    }
}
