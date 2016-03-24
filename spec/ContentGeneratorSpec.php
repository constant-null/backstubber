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

    function it_can_do_replacements_using_delimiter()
    {
        $testSquare = 'The captain of the Starship Enterprise is: [ captain ]';

        $this->setContent($testSquare);
        $this->setDelimiters('[', ']')
             ->replace('captain', 'James T Kirk')
             ->generate();
        $this->getContent()->shouldBeEqualTo('The captain of the Starship Enterprise is: James T Kirk');

        $testBlade = 'The captain of the Starship Enterprise is: {{ captain }}';
        $this->setContent($testBlade);
        $this->setDelimiters('{{', '}}')
            ->replace('captain', 'Jean Luc Picard')
            ->generate();

        // and even with extra spaces
        $testPercentage = 'The captain of the Starship Voyager is: %captain  %';
        $this->setContent($testPercentage);
        $this->setDelimiters('%', '%')
            ->replace('captain', 'Kathryn Janeway')
            ->generate();

        $this->getContent()->shouldBeEqualTo('The captain of the Starship Voyager is: Kathryn Janeway');
    }

    function it_should_avoid_naming_conflicts()
    {
        $constellations = 'Star, Star, StarStar, StarStarStar';

        $this->setContent($constellations);
        $this->replace('Star', 'x1')
             ->replace('StarStar', 'x2')
             ->replace('StarStarStar', 'x3')
             ->generate();
        $this->getContent()->shouldBeEqualTo('x1, x1, x2, x3');
    }
}
