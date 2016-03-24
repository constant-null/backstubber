<?php

namespace ConstantNull\Backstubber;

class StringProcessor
{
    /**
     * @var string processed content
     */
    protected $data;

    /**
     * array of searchable parts and their replacements
     *
     * @var array
     */
    protected $replacements = [];

    /**
     * Set data for processing
     *
     * @param $data string
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Get current data
     *
     * @return string string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Immediately replaces part of data
     *
     * @param $searchFor
     * @param $replaceWith
     * @return $this
     */
    public function doReplace($searchFor, $replaceWith)
    {
        $this->data = str_replace($searchFor, $replaceWith, $this->data);

        // for chaining

        return $this;
    }

    /**
     * Immediately replaces part of data using regular expression
     *
     * @param $searchPattern
     * @param $replaceWith
     * @return $this
     */
    public function doRegexpReplace($searchPattern, $replaceWith)
    {
        $this->data = preg_replace($searchPattern, $replaceWith, $this->data);
    }

    /**
     * Add replacement for processing
     *
     * @param $searchFor
     * @param $replaceWith
     * @return $this
     */
    public function replace($searchFor, $replaceWith)
    {
        $this->replacements[$searchFor] = $replaceWith;

        // for chaining

        return $this;
    }

    /**
     * Do actual replacements
     *
     * @return $this
     */
    public function process()
    {
        // Actual replacements

        $searchFor = array_keys($this->replacements);
        $replaceWith = array_values($this->replacements);
        $this->data = str_replace($searchFor, $replaceWith, $this->data);

        // clearing replacements
        $this->replacements = [];

        // for chaining

        return $this;
    }
}
