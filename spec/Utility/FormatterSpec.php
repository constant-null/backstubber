<?php

namespace spec\ConstantNull\Backstubber\Utility;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FormatterSpec extends ObjectBehavior
{
    function it_can_format_scalar()
    {
        // quotes added to string values
        $this::formatScalar('Mr. Spock')->shouldBeEqualTo("'Mr. Spock'");

        // return empty quotes on empty strings
        $this::formatScalar('')->shouldBeEqualTo("''");

        // returns numeric value as a string
        $this::formatScalar(1701)->shouldBeEqualTo('1701');

        // returns literal representation of booleans
        $this::formatScalar(true)->shouldBeEqualTo('true');
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

    function it_can_prepare_variable_line()
    {
        // preparing string variable
        $this::formatVariable('scienceOfficer', 'Mr. Spock')
            ->shouldBeEqualTo('$scienceOfficer = \'Mr. Spock\';');

        // preparing numeric variable
        $this::formatVariable('crewComplement', 430)
            ->shouldBeEqualTo('$crewComplement = 430;');

        // preparing boolean variable
        $this::formatVariable('shieldsUp', false)
            ->shouldBeEqualTo('$shieldsUp = false;');
    }

    function it_can_prepare_property_line()
    {
        // there is no need to test other variable types such string or boolean,
        // because this function use formatVariable function for base formatting
        $this::formatProperty('protected', 'photonTorpedoesCount', 5)
            ->shouldBeEqualTo('protected $photonTorpedoesCount = 5;');
    }
}
