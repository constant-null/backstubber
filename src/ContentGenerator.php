<?php

namespace ConstantNull\Backstubber;

class ContentGenerator
{
    protected $processor = null;

    /**
     * ContentGenerator constructor.
     */
    public function __construct()
    {
        $this->processor = new StringProcessor();
    }


    public function setContent($content)
    {
        $this->processor->setData($content);
    }

    public function getContent()
    {
        return $this->processor->getData();
    }

    public function replace($searchFor, $replaceWith)
    {
        $this->processor->replace($searchFor, $replaceWith);

        return $this;
    }

    public function generate()
    {
        $this->processor->process();

        return $this;
    }
}
