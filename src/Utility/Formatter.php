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
     * @param $name variable name
     * @param $value value of variable
     * @return string formatted
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
}
