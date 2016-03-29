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

    function it_handles_basic_var_replaces_with_delimiters()
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

        expect(file_get_contents('vfs://output/temp/EnterpriseClass.php'))->toBeEqualTo(file_get_contents('spec/Stubs/StarshipClass.assert'));
    }

    function it_handles_basic_var_replacements_with_prefix()
    {
        $officers = ['James T Kirk', 'Mr. Spock', 'Scott Montgomery', 'Pavel Chekov', 'Nyota Uhura', 'hikaru sulu'];
        $this->useStub('spec/Stubs/StarshipClass.prefix.stub')
//             ->withPrefix('Dummy')
             ->set('DummyOfficers', $officers)
             ->set('DummyCaptain', 'James T. Kirk')
             ->set('DummyCrew', 430)
             ->setRaw('DummyClass', 'Enterprise')
             ->setRaw('DummyClassNamespace', 'Federation\\Ships')
             ->generate('vfs://output/temp/EnterpriseClass.php');

        expect(file_get_contents('vfs://output/temp/EnterpriseClass.php'))->toBeEqualTo(file_get_contents('spec/Stubs/StarshipClass.assert'));
    }
}
