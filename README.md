# Meet the _Backstubber_, the PHP file generator

[![GitHub license](https://img.shields.io/github/license/constant-null/backstubber.svg?style=flat-square)](http://badges.mit-license.org/)
[![Packagist](https://img.shields.io/packagist/v/constant-null/backstubber.svg?style=flat-square)](https://packagist.org/packages/constant-null/backstubber)
[![Travis](https://img.shields.io/travis/constant-null/backstubber.svg?style=flat-square)](https://travis-ci.org/constant-null/backstubber/settings)

 Backstubber makes generation of PHP code files from templates fast and easy.
 Say no to tons of `str_replace`.

## installation

Backstubber can easily be installed using [composer](http://getcomposer.org/).
To do so, just run `php composer.phar require-dev constant-null/backstubber`.
Or you can add following to your `composer.json` file:

```json
{
    "require-dev": {
        "constant-null/backstubber": "~0.1"
    }
}
```

and then run:

```
$ php composer.phar update
```

## Usage

### FileGenerator

#### Basic substitution

Let's say we have a DummyStarship.stub template

```php
<?php
class DummyClass
{
    protected $captain = DummyCaptain;

    protected $officers = DummyOfficers;

    protected $crew = DummyCrew;
}
```

Where `$captain` supposed to be a string, `$officers` an array and `$crew` is numeric.
Well, just give required data to the backstubber using `set()` method!

```php
use ConstantNull/Backstubber/FileGenerator as Generator;

$generator = new Generator();
$generator->useStub('some/path/to/stubs/DummyStarship.stub')
          ->set('DummyOfficers', ['James T Kirk', 'Mr. Spock', 'Scott Montgomery'])
          ->set('DummyCaptain', 'James T. Kirk')
          ->set('DummyCrew', 430)
          ...
          // saving new file
          ->generate('path/to/generated/classes/EnterpriseClass.php');
```
The first parameter in the `set()` method is the string which needs to be replaced in template file,
while the second is variable needs to be inserted.
Backstubber will insert values according to they types, so in output we'll have something like that:

```php
    protected $captain = 'James T. Kirk';

    protected $officers = ['James T Kirk', 'Mr. Spock', 'Scott Montgomery'];

    protected $crew = 430;
```

But sometimes we need to insert the variable as it is, in case of Class or Namespace name for example.
For this purpose Backstubber has the `setRaw()` method.
This method has the same signature, but recieve only strings. Lets update previous example:

```php
use ConstantNull/Backstubber/FileGenerator as Generator;

$generator = new Generator();
$generator->useStub('some/path/to/stubs/DummyStarship.stub')
          ->set('DummyOfficers', ['James T Kirk', 'Mr. Spock', 'Scott Montgomery'])
          ->set('DummyCaptain', 'James T. Kirk')
          ->set('DummyCrew', 430)

          // newly added methods
          ->setRaw('DummyClass', 'Enterprise')
          ->setRaw('DummyClassNamespace', 'Federation\\Ships')

          // saving new file
          ->generate('path/to/generated/classes/EnterpriseClass.php');
```

So in result file `EnterpriseClass.php` will contain the following:

```php
<?php
namespace Federation\Ships;

class Enterprise
{
    protected $captain = 'James T. Kirk';

    protected $officers = ['James T Kirk', 'Mr. Spock', 'Scott Montgomery'];

    protected $crew = 430;
}
```

#### Using prefixes

If you using the same prefix for all your placeholders (just like "Dummy" prefix in example above), you can use `withPrefix` method.
So the following code and the one shown above, will have the same results.

```php
use ConstantNull/Backstubber/FileGenerator as Generator;

$generator = new Generator();
$generator->useStub('some/path/to/stubs/DummyStarship.stub')
          ->withPrefix('Dummy')
          ->set('Officers', ['James T Kirk', 'Mr. Spock', 'Scott Montgomery'])
          ->set('Captain', 'James T. Kirk')
          ->set('Crew', 430)

          // newly added methods
          ->setRaw('Class', 'Enterprise')
          ->setRaw('ClassNamespace', 'Federation\\Ships')

          // saving new file
          ->generate('path/to/generated/classes/EnterpriseClass.php');
```

#### Templates with delimiters

Using the basic text substitution with or without prefixes like "Dummy" as in the example above is good,
but sometimes you might want to specify parts to be replaced more explicitly.
And this is where delimiters shows up!

Let's use the Laravel Blade style delimiters "{{" and "}}" for example, then our template file will looks like this:

```php
<?php
namespace {{ namespace }};

class {{ class }}
{
    protected $captain = {{ captain }};

    protected $officers = {{ officers }};

    protected $crew = {{ crew }};
}
```
And now we just tell the backstubber to use our delimiters

```php
use ConstantNull/Backstubber/FileGenerator as Generator;

$generator = new Generator();
$generator->useStub('some/path/to/stubs/DummyStarship.stub')

          // set delimiters
          ->withDelimiters('{{', '}}')

          // assign substitutions
          ->set('officers', ['James T Kirk', 'Mr. Spock', 'Scott Montgomery'])
          ->set('captain', 'James T. Kirk')
          ->set('crew', 430)
          ->setRaw('class', 'Enterprise')
          ->setRaw('namespace', 'Federation\\Ships')

          // saving new file
          ->generate('path/to/generated/classes/EnterpriseClass.php');
```

And this is it!

### Multi-line arrays

Starting from version 0.3.0 the backstubber has support of multi-line array formatting.

With default settings associative arrays will be formatted using multi-line format and non-associative arrays using single-line format
(just like in the examples shown above), so the following:

```php
...
$officers = [
    'Captain' => 'Jean Luc Picard',
    'First officer' => 'William T. Riker',
    'Tactical Officer' => 'Tasha Yar'
];


$generator->set('officers', $officers)

```

will result in:

```php
$officers = [
    'Captain' => 'Jean Luc Picard',
    'First officer' => 'William T. Riker',
    'Tactical Officer' => 'Tasha Yar'
];
```

Default behaviour can be changed using `ConstantNull\Backstubber\Utility\Formatter::setArrayMode`.
For now, method accepts one of three parameters, which is:
1. `Formatter::ARR_MODE_AUTO` for default behaviour
2. `Formatter::ARR_MODE_MULTILINE` force backstabber to format all arrays in multi-line format
3. `Formatter::ARR_MODE_SINGLELINE` force backstabber to format all arrays in single-line format

#### Indentation

For correct multi-line formatting, the base indentation must be set.
You can do it globally using `ConstantNull\Backstubber\Utility\Formatter::setBaseIndent` (for now it is the only way, but most likely it will change in the future).

`setBaseIndent` receives number of tabulations (soft), the default value is 0.
You can see the 1 tab indentation in example below (generated indents shown by arrows for clarity)

```php
class StarshipEnterprise
{
    $officers = [
<-->    'Captain' => 'Jean Luc Picard',
<-->    'First officer' => 'William T. Riker',
<-->    'Tactical Officer' => 'Tasha Yar'
<-->];
}
```

## Authors

This library was developed by me, [Mark Belotskiy](https://github.com/constant-null). Special thanks (as promised) to my friend [Dmitriy Shulgin]() for helping with the name of the library.