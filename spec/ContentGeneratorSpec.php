<?php

namespace spec\ConstantNull\Backstubber;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContentGeneratorSpec extends ObjectBehavior
{
    function it_can_do_basic_replacements()
    {
        $data = 'This is the story of the StarshipName. Its mission: MissionDescription';

        $this->setContent($data);

        $this->replace('StarshipName', 'Starship Enterprise')
             ->replace('MissionDescription', 'to explore the strange new worlds where no man has gone before')
             ->generate();

        $this->getContent()->shouldBe(
            'This is the story of the Starship Enterprise. Its mission: to explore the strange new worlds where no man has gone before'
        );
    }
}
