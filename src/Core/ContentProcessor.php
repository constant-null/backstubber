<?php

namespace ConstantNull\Backstubber\Core;

class ContentProcessor
{
    /**
     * processed content
     *
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $globalPrefix = '';

    /**
     * @var string
     */
    protected $beginDelimiter = '';


    /**
     * @var string
     */
    protected $endDelimiter = '';
    /**
     * Store searchable parts and their replacements
     * array index - part to be replaced,
     * array value - replacement itself
     *
     * @var array
     */
    protected $replacements = [];

    /**
     * Set prefix which will prepend all substitutions
     *
     * @param $prefix string
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->globalPrefix = $prefix;

        // for chaining
        return $this;
    }

    /**
     * Check if global prefix was set
     *
     * @return bool
     */
    public function isPrefixUsed()
    {
        return (bool)$this->globalPrefix;
    }

    /**
     * Check if the delimiters was set
     * (which means replacing will be done using regular expressions)
     *
     * @return bool
     */
    public function isDelimitersUsed()
    {
        return ($this->beginDelimiter && $this->endDelimiter);
    }

    /**
     * Set begin and end delimiters which will be used to detect parts to be replaced
     *
     * @param $begin string
     * @param $end string
     * @return $this
     */
    public function setDelimiters($begin, $end)
    {
        $this->beginDelimiter = $begin;

        $this->endDelimiter = $end;

        // for chaining
        return $this;
    }

    /**
     * Set content for processing
     *
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * get processed content
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Add replacement to processing list
     *
     * @param $searchFor string
     * @param $replaceWith string
     * @return StringProcessor
     */
    public function replace($searchFor, $replaceWith)
    {
        $this->replacements[$searchFor] = $replaceWith;

        // for chaining

        return $this;
    }

    /**
     * Immediately replaces part of data using regular expression
     *
     * @param $searchPattern string
     * @param $replaceWith string
     * @return StringProcessor
     */
    public function doRegexpReplace($searchPattern, $replaceWith)
    {
        $this->content = preg_replace($searchPattern, $replaceWith, $this->content);

        return $this;
    }

    /**
     * Immediately replaces part of data
     *
     * @param $searchFor string
     * @param $replaceWith string
     * @return StringProcessor
     */
    public function doReplace($searchFor, $replaceWith)
    {
        $this->content = str_replace($searchFor, $replaceWith, $this->content);

        // for chaining

        return $this;
    }

    /**
     * Replace data using delimiters and regular expressions
     *
     * @param $searchFor string
     * @param $replaceWith string
     */
    private function replaceWithRegexp($searchFor, $replaceWith)
    {
        $pattern = "/\\{$this->beginDelimiter}\\s*{$searchFor}\\s*\\{$this->endDelimiter}/u";

        $this->doRegexpReplace($pattern, $replaceWith);
    }

    /**
     * Replace data using str_replace using prefix (if set)
     *
     * @param $searchFor string
     * @param $replaceWith string
     */
    private function replaceBasic($searchFor, $replaceWith)
    {
        if ($this->isPrefixUsed()) {
            $searchFor = $this->globalPrefix . $searchFor;
        }

        $this->doReplace($searchFor, $replaceWith);
    }

    /**
     * Start replacing data
     *
     * @param $forceOrder bool keep user defined order of replacements (conflicts possible)
     * @return $this
     */
    public function process($forceOrder = false)
    {
         // sort the replacements by descending
         // (in that way some problems with replacements naming can be avoided)
        if (!$forceOrder) {
            array_multisort(
                array_map('mb_strlen', array_keys($this->replacements)),
                SORT_DESC,
                $this->replacements
            );
        }

        // decide which function will be used
        $replaceFunc = $this->isDelimitersUsed() ? 'replaceWithRegexp' : 'replaceBasic';

        foreach ($this->replacements as $searchFor => $replaceWith) {
            $this->$replaceFunc($searchFor, $replaceWith);
        }

        // for chaining
        return $this;
    }
}
