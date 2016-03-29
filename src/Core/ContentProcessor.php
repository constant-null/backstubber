<?php

namespace ConstantNull\Backstubber\Core;

class ContentProcessor extends StringProcessor
{
    /**
     * @var string
     */
    protected $beginDelimiter = '';

    /**
     * @var string
     */
    protected $endDelimiter = '';

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
     * Set content for processing (setData alias)
     *
     * @param $content
     */
    public function setContent($content)
    {
        $this->setData($content);
    }

    /**
     * get processed content (getData alias)
     * @return string
     */
    public function getContent()
    {
        return $this->getData();
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
     * Replace data using str_replace
     *
     * @param $searchFor string
     * @param $replaceWith string
     */
    private function replaceBasic($searchFor, $replaceWith)
    {
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
