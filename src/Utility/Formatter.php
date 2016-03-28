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
     * Add quotes at the ends of the string
     *
     * @param $sting
     * @return string
     */
    public function formatString($sting)
    {
        return "'$sting'";
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
        if (is_string($value)) {
            $value = self::formatString($value);
        }

        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }

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
