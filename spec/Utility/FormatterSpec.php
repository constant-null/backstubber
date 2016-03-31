<?php

namespace spec\ConstantNull\Backstubber\Utility;

use ConstantNull\Backstubber\Utility\Formatter;
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

    function it_can_format_empty_arrays()
    {
        $this::setIndent(1, 2, 1);

        $this::setArrayMode(Formatter::ARR_MODE_AUTO);
        $this::formatArray([], false)->shouldBeEqualTo('');
        $this::formatArray([])->shouldBeEqualTo('[]');

        $this::setArrayMode(Formatter::ARR_MODE_INLINE);
        $this::formatArray([], false)->shouldBeEqualTo('');
        $this::formatArray([])->shouldBeEqualTo('[]');

        $this::setArrayMode(Formatter::ARR_MODE_MULTILINE);
        $this::formatArray([], false)->shouldBeEqualTo('');
        $this::formatArray([])->shouldBeEqualTo('[]');
    }

    function it_can_format_array_with_brackets()
    {
        $races = ['Klingon', 'Vulcan', 'Andorian', 'Borg'];

        // reset formatting mode to default
        $this::setArrayMode(Formatter::ARR_MODE_AUTO);

        //set indent for this example
        $this::setIndent(0, 3, 2);

        // default array formatting:
        // empty array
        $this::formatArray([])->shouldBeEqualTo('[]');

        // array with data
        $formattedRaces = "['Klingon', 'Vulcan', 'Andorian', 'Borg']";
        $this::formatArray($races)->shouldBeEqualTo($formattedRaces);

        $this::setArrayMode(Formatter::ARR_MODE_MULTILINE);

        $formattedRaces = "[
            'Klingon', 
            'Vulcan', 
            'Andorian', 
            'Borg'
        ]";

        $this::formatArray($races)->shouldBeEqualTo($formattedRaces);
    }

    protected function it_can_format_array_without_brackets()
    {
        $races = ['Klingon', 'Vulcan', 'Andorian', 'Borg'];

        // reset formatting mode to default
        $this::setArrayMode(Formatter::ARR_MODE_AUTO);

        //set indent for this example
        $this::setIndent(0, 3, 2);

        // array with data
        $formattedRaces = "'Klingon', 'Vulcan', 'Andorian', 'Borg'";
        $this::formatArray($races, false)->shouldBeEqualTo($formattedRaces);

        // multiline formating
        $this::setArrayMode(Formatter::ARR_MODE_MULTILINE);

        $formattedRaces = "
            'Klingon', 
            'Vulcan', 
            'Andorian', 
            'Borg'
        ";
        $this::formatArray($races, false)->shouldBeEqualTo($formattedRaces);
    }

    function it_can_format_associative_array_with_brackets()
    {
        $crew = [
            'Captain' => 'Jean Luc Picard',
            'First officer' => 'William T. Riker',
            'Tactical Officer' => 'Tasha Yar'
        ];

        // reset formatting mode to default
        $this::setArrayMode(Formatter::ARR_MODE_AUTO);

        // set indent for this example
        $this::setIndent(0, 3, 2);

        // inline associative array
        $formattedRaces = "['Captain' => 'Jean Luc Picard', 'First officer' => 'William T. Riker', 'Tactical Officer' => 'Tasha Yar']";

        $this::setArrayMode(Formatter::ARR_MODE_INLINE);
        $this::formatArray($crew)->shouldBeEqualTo($formattedRaces);

        // multiline arrays
        $formattedCrew = "[
            'Captain' => 'Jean Luc Picard', 
            'First officer' => 'William T. Riker', 
            'Tactical Officer' => 'Tasha Yar'
        ]";

        // associative array should automatically be formatted as multiline
        $this::setArrayMode(Formatter::ARR_MODE_AUTO);
        $this::formatArray($crew)->shouldBeEqualTo($formattedCrew);

        $this::setArrayMode(Formatter::ARR_MODE_MULTILINE);
        $this::formatArray($crew)->shouldBeEqualTo($formattedCrew);
    }

    function it_can_format_associative_array_without_brackets()
    {
        $crew = [
            'Captain' => 'Jean Luc Picard',
            'First officer' => 'William T. Riker',
            'Tactical Officer' => 'Tasha Yar'
        ];

        // reset formatting mode to default
        $this::setArrayMode(Formatter::ARR_MODE_AUTO);

        // set indent for this example
        $this::setIndent(0, 3, 2);

        // inline associative array
        $formattedRaces = "'Captain' => 'Jean Luc Picard', 'First officer' => 'William T. Riker', 'Tactical Officer' => 'Tasha Yar'";

        $this::setArrayMode(Formatter::ARR_MODE_INLINE);
        $this::formatArray($crew, false)->shouldBeEqualTo($formattedRaces);

        // multiline arrays
        $formattedCrew = "
            'Captain' => 'Jean Luc Picard', 
            'First officer' => 'William T. Riker', 
            'Tactical Officer' => 'Tasha Yar'
        ";

        // associative array should automatically be formatted as multiline
        $this::setArrayMode(Formatter::ARR_MODE_AUTO);
        $this::formatArray($crew, false)->shouldBeEqualTo($formattedCrew);

        $this::setArrayMode(Formatter::ARR_MODE_MULTILINE);
        $this::formatArray($crew, false)->shouldBeEqualTo($formattedCrew);
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
