<?php

namespace ConstantNull\Backstubber;

use ConstantNull\Backstubber\Core\ContentProcessor;
use ConstantNull\Backstubber\Utility\Formatter;

/**
 * File  generator service
 */
class FileGenerator extends ContentProcessor
{
    /**
     * Set stub (template) file used in generation
     *
     * @param $stubPath string path to stub (template file)
     *
     * @return $this
     */
    public function useStub($stubPath)
    {
        $this->setContent(file_get_contents($stubPath));

        // return is for chaining
        return $this;
    }

    /**
     * Set begin and end delimiters which will be used to detect parts to be replaced (alias for setDelimiters function)
     *
     * @param $begin string
     * @param $end   string
     *
     * @return $this
     */
    public function withDelimiters($begin, $end)
    {
        // return is for chaining
        return $this->setDelimiters($begin, $end);
    }

    /**
     * Set prefix which will prepend all substitutions (alias for setPrefix function)
     *
     * @param $prefix string
     * @return $this
     */
    public function withPrefix($prefix)
    {
        return $this->setPrefix($prefix);
    }

    /**
     * Add variable(s) to replacement list
     * it will automatically format scalar types and arrays,
     * so they text representation will be inserted
     *
     * @param $name  array|string
     * @param $value null|string|array
     * @return $this
     */
    public function set($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $innerName => $innerValue) {
                $this->replace($innerName, $this->formatValue($innerValue));
            }
        } else {
            $this->replace($name, $this->formatValue($value));
        }

        // return is for chaining
        return $this;
    }

    /**
     * Add variable(s) to replacement list, without any formatting.
     * May be usefull when inserting class/variable/trait names, control structures, etc.
     *
     * @param $name  array|string
     * @param $value null|array|string
     * @return $this
     */
    public function setRaw($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $innerName => $innerValue) {
                $this->replace($innerName, $innerValue);
            }
        } else {
            $this->replace($name, $value);
        }

        // return is for chaining
        return $this;
    }

    /**
     * save generated file to path
     * returns true if file was successfully created false otherwise
     *
     * @param $outputPath string
     * @return bool
     */
    public function generate($outputPath)
    {
        // do content replacement
        $this->process();

        // check if directory exist, if not create it
        $baseDir = dirname($outputPath);
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 700, true);
        }

        return (bool)file_put_contents($outputPath, $this->getContent());
    }

    /**
     * Format a value before processing.
     *
     * @param array|string $value
     * @return array|string
     */
    protected function formatValue($value)
    {
        if (is_array($value)) {
            return Formatter::formatArray($value);
        }

        return Formatter::formatScalar($value);
    }
}
