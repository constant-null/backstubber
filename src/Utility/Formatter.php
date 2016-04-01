<?php

namespace ConstantNull\Backstubber\Utility;

use Illuminate\Support\Arr;

class Formatter
{
    const ARR_MODE_AUTO = 0;

    const ARR_MODE_ONELINE = 1;

    const ARR_MODE_MULTILINE = 2;

    protected static $arrayMode = self::ARR_MODE_AUTO;

    protected static $firstLineIndent = 0;

    protected static $midLinesIndent = 1;

    protected static $lastLineIndent = 0;

    /**
     * @param array $array
     * @return bool
     */
    protected static function isArrayMultiline($array)
    {
        if (empty($array)) return false;

        switch (self::getArrayMode()) {
            case self::ARR_MODE_ONELINE:
                $isMultiline = false;
                break;
            case self::ARR_MODE_MULTILINE:
                $isMultiline = true;
                break;
            default: // for ARR_MODE_AUTO
                $isMultiline = Arr::isAssoc($array) ? true : false;
                return $isMultiline;
        }
        return $isMultiline;
    }

    /**
     * @param integer $tabs
     * @return mixed
     */
    protected static function indent($tabs)
    {
        // convert tabs to spaces (PSR-2)
        return str_pad('', $tabs * 4);
    }

    /**
     * @param array $array
     * @return array
     */
    protected static function prepareArrayLines(array $array)
    {
        $isAssoc = Arr::isAssoc($array);

        array_walk($array, function (&$value, $index, $withIndexes = false) {
            $value = self::formatScalar($value);

            if ($withIndexes) {
                $value = self::formatScalar($index) . ' => ' . $value;
            }
        }, $isAssoc);

        return $array;
    }

    /**
     * @param string $text
     * @return array
     */
    protected static function indentLines($text)
    {
        $lines = explode(PHP_EOL, $text);

        $indentedLines[] = self::indent(self::$firstLineIndent) . array_shift($lines);

        while (count($lines) > 1) {
            $indentedLines[] = self::indent(self::$midLinesIndent) . array_shift($lines);
        }

        $indentedLines[] = self::indent(self::$lastLineIndent) . array_shift($lines);

        return implode(PHP_EOL, $indentedLines);
    }

    public function setArrayMode($arrayMode)
    {
        self::$arrayMode = $arrayMode;
    }

    public function getArrayMode()
    {
        return self::$arrayMode;
    }

    public function setIndent($firstLine, $midLines, $lastLine)
    {
        self::$firstLineIndent = $firstLine;
        self::$midLinesIndent = $midLines;
        self::$lastLineIndent = $lastLine;
    }

    /**
     * Return text representation of array
     *
     * @param array $array input array
     * @param bool $braces add array braces to output
     * @return string
     */
    public static function formatArray(array $array, $braces = true)
    {
        $isMultiline = self::isArrayMultiline($array);

        $array = self::prepareArrayLines($array);

        $eol = $isMultiline ? PHP_EOL : '';

        $output = implode($array, ', ' . $eol);

        if ($braces) {
            $output = implode(['[', $output, ']'], $eol);
        } else {
            $output = $eol . $output . $eol;
        }

        // region do indention
        if ($isMultiline) {
            $output = self::indentLines($output);
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
