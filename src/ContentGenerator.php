<?php

namespace ConstantNull\Backstubber;

class ContentGenerator
{
    /**
     * @var StringProcessor
     */
    protected $processor = null;

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
     * ContentGenerator constructor.
     */
    public function __construct()
    {
        $this->processor = new StringProcessor();
    }

    /**
     * Check if the delimiters was set
     * (which means replacing will be done using regular expressions)
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
     * @param $content
     */
    public function setContent($content)
    {
        $this->processor->setData($content);
    }

    /**
     * get processed content
     * @return string
     */
    public function getContent()
    {
        return $this->processor->getData();
    }

    /**
     * Add replacement to processing list
     *
     * @param $searchFor string
     * @param $replaceWith string
     * @return $this
     */
    public function replace($searchFor, $replaceWith)
    {
        $this->replacements[$searchFor] = $replaceWith;

        // for chaining

        return $this;
    }

    /**
     * Replace data using delimiters and regular expressions
     *
     * @param $searchFor string
     * @param $replaceWith string
     */
    protected function replaceWithRegexp($searchFor, $replaceWith)
    {
        $pattern = "/\\{$this->beginDelimiter}\\s*{$searchFor}\\s*\\{$this->endDelimiter}/u";

        $this->processor->doRegexpReplace($pattern, $replaceWith);
    }

    /**
     * Replace data using str_replace
     *
     * @param $searchFor string
     * @param $replaceWith string
     */
    protected function replaceBasic($searchFor, $replaceWith)
    {
        $this->processor->replace($searchFor, $replaceWith);
    }


    /**
     * Start replacing data;
     *
     * @return $this
     */
    public function generate()
    {
        // decide which function will be used
        $replaceFunc = ( $this->isDelimitersUsed() ) ? 'replaceWithRegexp' : 'replaceBasic';

        foreach ( $this->replacements as $searchFor => $replaceWith ) {
            $this->$replaceFunc($searchFor, $replaceWith);
        }

        $this->processor->process();

        // for chaining

        return $this;
    }
}
