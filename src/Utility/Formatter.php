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

    public function formatString($sting)
    {
        return "'$sting'";
    }
}
