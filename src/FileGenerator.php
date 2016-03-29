<?php

namespace ConstantNull\Backstubber;

use ConstantNull\Backstubber\Core\ContentProcessor;
use ConstantNull\Backstubber\Utility\Formatter;

class FileGenerator extends ContentProcessor
{
    /**
     * set stub (template) file used in generation
     *
     * @param $stubPath string path to stub (template file)
     * @return $this
     */
    public function useStub($stubPath)
    {
        $this->setContent(file_get_contents($stubPath));

        // return is for chaining
        return $this;
    }

    /**
     * Set begin and end delimiters which will be used to detect parts to be replaced
     *
     * @param $begin string
     * @param $end string
     * @return $this
     */
    public function withDelimiters($begin, $end)
    {
        // return is for chaining
        return $this->setDelimiters($begin, $end);
    }

    /**
     * Add variable to replacement list
     * it will automatically format scalar types and arrays,
     * so they text representation will be inserted
     *
     * @param $name string
     * @param $value mixed
     * @return $this
     */
    public function set($name, $value)
    {
        if (is_array($value)) {
            $formattedValue = Formatter::formatArray($value);
        } else {
            $formattedValue = Formatter::formatScalar($value);
        }

        // return is for chaining
        return $this->replace($name, $formattedValue);
    }

    /**
     * Add variable to replacement list, without any formatting.
     * May be usefull when inserting class/variable/trait names, control structures, etc.
     *
     * @param $name
     * @param $value
     * @return $this
     */
    public function setRaw($name, $value)
    {
        // return is for chaining
        return $this->replace($name, $value);
    }

    /**
     * save generated file to path
     *
     * @param $outputPath string
     */
    public function generate($outputPath)
    {
        $this->process();
        file_put_contents($outputPath, $this->getContent());
    }
}
