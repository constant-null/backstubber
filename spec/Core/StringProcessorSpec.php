<?php

namespace spec\ConstantNull\Backstubber\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StringProcessorSpec extends ObjectBehavior
{
    function it_is_receives_and_returns_data()
    {
        $data = 'Live long and prosper';

        $this->setData($data);
        $this->getData()->shouldBeEqualTo($data);
    }

    function it_can_replace_specified_parts_of_data()
    {
        $data = 'This is the story of the StarshipName. Its mission: MissionDescription';

        $this->setData($data);

        $this->replace('StarshipName', 'Starship Enterprise')
             ->replace('MissionDescription', 'to explore the strange new worlds where no man has gone before')
             ->process();

        $this->getData()->shouldBe(
            'This is the story of the Starship Enterprise. Its mission: to explore the strange new worlds where no man has gone before'
        );
    }

    function it_can_do_replaces_immediately()
    {
        $starshipName = 'Enterprise NCC 1701';

        $this->setData($starshipName);

        $this->doReplace('1701', '1701 A');
        $this->getData()->shouldBeEqualTo('Enterprise NCC 1701 A');

        $this->doReplace('1701 A', '1701 B');
        $this->getData()->shouldBeEqualTo('Enterprise NCC 1701 B');

        $this->doReplace('1701 B', '1701 C');
        $this->getData()->shouldBeEqualTo('Enterprise NCC 1701 C');
    }

    function it_can_replace_using_regexp()
    {
        $info = 'The captain of the Starship Enterprise is: [ captain ]';

        $this->setData($info);
        $this->doRegexpReplace('/\[\s*captain\s*\]/', 'James T Kirk');

        $this->getData()->shouldBeEqualTo('The captain of the Starship Enterprise is: James T Kirk');
    }
}
