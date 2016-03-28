<?php

namespace spec\ConstantNull\Backstubber\Utility;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FormatterSpec extends ObjectBehavior
{
    function it_can_format_string()
    {
        // actually all that is needed is add quotes
        $this::formatString('Mr. Spock')->shouldBeEqualTo("'Mr. Spock'");
    }

    function it_can_format_array()
    {
        $races = ['Klingon', 'Vulcan', 'Andorian', 'Borg'];

        // default array formatting:
        // empty array
        $this::formatArray([])->shouldBeEqualTo('[]');

        // array with data
        $formattedRaces = "['Klingon', 'Vulcan', 'Andorian', 'Borg']";
        $this::formatArray($races)->shouldBeEqualTo($formattedRaces);


        // array formatting without brackets
        // empty array
        $this::formatArray([], false)->shouldBeEqualTo('');

        // array with data
        $formattedRaces = "'Klingon', 'Vulcan', 'Andorian', 'Borg'";
        $this::formatArray($races, false)->shouldBeEqualTo($formattedRaces);
    }
}
