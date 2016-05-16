<?php

namespace spec\ConstantNull\Backstubber\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContentProcessorSpec extends ObjectBehavior
{
    function it_is_receives_and_returns_data()
    {
        $data = 'Live long and prosper';
        $this->setContent($data);
        $this->getContent()->shouldBeEqualTo($data);
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
        $this->doRegexpReplace('/(?\'target\'\[\s*captain\s*\])/', 'James T Kirk');
        $this->getContent()->shouldBeEqualTo('The captain of the Starship Enterprise is: James T Kirk');
    }

    function it_can_do_basic_replacements()
    {
        $data = 'This is the story of the StarshipName. Its mission: MissionDescription';

        $this->setContent($data);

        $this->replace('StarshipName', 'Starship Enterprise')
             ->replace('MissionDescription', 'to explore the strange new worlds where no man has gone before')
             ->process();

        $this->getContent()->shouldBe(
            'This is the story of the Starship Enterprise. Its mission: to explore the strange new worlds where no man has gone before'
        );
    }

    function it_can_do_replacements_with_prefix()
    {
        $data = 'This is the story of the StarshipName. Its mission: StarshipMissionDescription';

        $this->setContent($data);

        $this->setPrefix('Starship')
             ->replace('Name', 'Starship Enterprise')
             ->replace('MissionDescription', 'to explore the strange new worlds where no man has gone before')
             ->process();

        $this->getContent()->shouldBe(
            'This is the story of the Starship Enterprise. Its mission: to explore the strange new worlds where no man has gone before'
        );
    }

    function it_can_do_replacements_using_delimiter()
    {
        $testSquare = 'The captain of the Starship Enterprise is: [ captain ]';

        $this->setContent($testSquare);
        $this->setDelimiters('[', ']')
             ->replace('captain', 'James T Kirk')
             ->process();
        $this->getContent()->shouldBeEqualTo('The captain of the Starship Enterprise is: James T Kirk');

        $testBlade = 'The captain of the Starship Enterprise is: {{ captain }}';
        $this->setContent($testBlade);
        $this->setDelimiters('{{', '}}')
             ->replace('captain', 'Jean Luc Picard')
             ->process();

        // and even with extra spaces
        $testPercentage = 'The captain of the Starship Voyager is: %captain  %';
        $this->setContent($testPercentage);
        $this->setDelimiters('%', '%')
             ->replace('captain', 'Kathryn Janeway')
             ->process();

        $this->getContent()->shouldBeEqualTo('The captain of the Starship Voyager is: Kathryn Janeway');
    }

    function it_should_avoid_naming_conflicts()
    {
        $constellations = 'Star, Star, StarStar, StarStarStar';

        $this->setContent($constellations);
        $this->replace('Star', 'x1')
             ->replace('StarStar', 'x2')
             ->replace('StarStarStar', 'x3')
             ->process();
        $this->getContent()->shouldBeEqualTo('x1, x1, x2, x3');
    }
}
