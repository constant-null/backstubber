<?php

namespace spec\ConstantNull\Backstubber;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContentProcessorSpec extends ObjectBehavior
{
    function it_is_receives_and_returns_content()
    {
        $content = 'Live long and prosper';

        $this->setContent($content);
        $this->getContent()->shouldBeEqualTo($content);
    }

    function it_can_replace_specified_parts_of_content()
    {
        $content = 'This is the story of the StarshipName. Its mission: MissionDescription';

        $this->setContent($content);

        $this->replace('StarshipName', 'Starship Enterprise')
             ->replace('MissionDescription', 'to explore the strange new worlds where no man has gone before')
             ->process();

        $this->getContent()->shouldBe(
            'This is the story of the Starship Enterprise. Its mission: to explore the strange new worlds where no man has gone before'
        );
    }

    function it_can_do_replaces_immediately()
    {
        $starshipName = 'Enterprise NCC 1701';

        $this->setContent($starshipName);

        $this->doReplace('1701', '1701 A');
        $this->getContent()->shouldBeEqualTo('Enterprise NCC 1701 A');

        $this->doReplace('1701 A', '1701 B');
        $this->getContent()->shouldBeEqualTo('Enterprise NCC 1701 B');

        $this->doReplace('1701 B', '1701 C');
        $this->getContent()->shouldBeEqualTo('Enterprise NCC 1701 C');
    }

    function it_can_replace_using_regexp()
    {
        $info = 'The captain of the Starship Enterprise is: [ captain ]';

        $this->setContent($info);
        $this->doRegexpReplace('/\[\s*captain\s*\]/', 'James T Kirk');

        $this->getContent()->shouldBeEqualTo('The captain of the Starship Enterprise is: James T Kirk');
    }
}
