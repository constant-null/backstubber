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
    public function formatArray(array $array, $braces = true)
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
     * @param $value scalar type value
     * @return string
     */
    public function formatScalar($value)
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
     * @param $name variable name
     * @param $value value of the variable
     * @return string
     */
    public function formatVariable($name, $value)
    {
        $value = self::formatScalar($value);

        return "\$$name = $value;";
    }

    /**
     * Generates stub ready property
     *
     * @param $keywords property keywords such as protected | public static
     * @param $name property name
     * @param $value value of the property
     * @return string
     */
    public function formatProperty($keywords, $name, $value)
    {
        return $keywords . ' ' . self::formatVariable($name, $value);
    }
}
