<?php

namespace spec\ConstantNull\Backstubber;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use org\bovigo\vfs\vfsStream;

class FileGeneratorSpec extends ObjectBehavior
{
    protected $vfs;

    function let()
    {
        $this->vfs = vfsStream::setup('output');
    }

    function it_handles_basic_var_replaces()
    {
        $officers = ['James T Kirk', 'Mr. Spock', 'Scott Montgomery', 'Pavel Chekov', 'Nyota Uhura', 'hikaru sulu'];
        $this->useStub('spec/Stubs/StarshipClass.blade.stub')
             ->withDelimiters('{{', '}}')
             ->set('officers', $officers)
             ->set('captain', 'James T. Kirk')
             ->set('crew', 430)
             ->setRaw('class', 'Enterprise')
             ->setRaw('namespace', 'Federation\\Ships')
             ->generate('vfs://output/temp/EnterpriseClass.php');

        expect(file_get_contents('vfs://output/temp/EnterpriseClass.php'))->toBeEqualTo(file_get_contents('spec/Stubs/StarshipClass.blade.assert'));
    }
}
