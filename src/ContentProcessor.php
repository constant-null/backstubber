<?php

namespace ConstantNull\Backstubber;

class ContentProcessor
{
    /**
     * @var string processed content
     */
    protected $content;

    /**
     * array of searchable parts and their replacements
     *
     * @var array
     */
    protected $replacements = [];

    /**
     * Set content for processing
     *
     * @param $content string
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get current content
     *
     * @return string string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Immediately replaces part of content
     *
     * @param $searchFor
     * @param $replaceWith
     * @return $this
     */
    public function doReplace($searchFor, $replaceWith)
    {
        $this->content = str_replace($searchFor, $replaceWith, $this->content);

        // for chaining

        return $this;
    }

    /**
     * Immediately replaces part of content using regular expression
     *
     * @param $searchPattern
     * @param $replaceWith
     * @return $this
     */
    public function doRegexpReplace($searchPattern, $replaceWith)
    {
        $this->content = preg_replace($searchPattern, $replaceWith, $this->content);
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
        $this->content = str_replace($searchFor, $replaceWith, $this->content);

        // clearing replacements
        $this->replacements = [];

        // for chaining

        return $this;
    }
}
