# Meet the _Backstubber_, the PHP file generator

[![GitHub license](https://img.shields.io/github/license/mashape/apistatus.svg?style=flat-square)](https://github.com/constant-null/backstubber)
[![GitHub release](https://img.shields.io/github/release/qubyte/rubidium.svg?style=flat-square)](https://github.com/constant-null/backstubber)
[![Travis](https://img.shields.io/travis/constant-null/backstubber.svg?style=flat-square)](https://travis-ci.org/constant-null/backstubber/settings)

 Backstubber makes generation of PHP code files from templates fast and easy.
 Say no to tons of `str_replace`.

## installition

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

## Basic usage

### FileGenerator

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
          //saving new file
          ->generate('path/to/generated/classes/EnterpriseClass.php');
```
The first parameter in the `set()` method is the string which needs to be replaced in template file, while the second is variable needs to be inserted.
Backstubber will insert values according to they types, so in output we'll have something like that:

```php
    protected $captain = 'James T. Kirk';

    protected $officers = ['James T Kirk', 'Mr. Spock', 'Scott Montgomery'];

    protected $crew = 430;
```

But sometimes we need to insert the variable as it is, in case of Class or Namespace name for expample. For this purpose Backstubber has the `setRaw()` method.
This method has the same signature, but recieve only strings. Lets update previous exaple:

```php
use ConstantNull/Backstubber/FileGenerator as Generator;

$generator = new Generator();
$generator->useStub('some/path/to/stubs/DummyStarship.stub')
          ->set('DummyOfficers', ['James T Kirk', 'Mr. Spock', 'Scott Montgomery'])
          ->set('DummyCaptain', 'James T. Kirk')
          ->set('DummyCrew', 430)

          //newly added methods
          ->setRaw('DummyClass', 'Enterprise')
          ->setRaw('DummyClassNamespace', 'Federation\\Ships')

          //saving new file
          ->generate('path/to/generated/classes/EnterpriseClass.php');
```

So in result file `EnterpriseClass.php` will contain the folowing:

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

## Authors

This library was developed by me, [Mark Belotskiy](https://github.com/constant-null). Special thanks (as promised) to my friend [Dmitriy Shulgin]() for helping with the name of the library.