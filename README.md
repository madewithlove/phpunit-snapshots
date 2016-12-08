# PHPUnit Snapshots

[![Build Status](http://img.shields.io/travis/madewithlove/phpunit-snapshots.svg?style=flat-square)](https://travis-ci.org/madewithlove/phpunit-snapshots)
[![Latest Stable Version](http://img.shields.io/packagist/v/madewithlove/phpunit-snapshots.svg?style=flat-square)](https://packagist.org/packages/madewithlove/phpunit-snapshots)
[![Total Downloads](http://img.shields.io/packagist/dt/madewithlove/phpunit-snapshots.svg?style=flat-square)](https://packagist.org/packages/madewithlove/phpunit-snapshots)

This trait allows you to use [Jest-like](https://facebook.github.io/jest/) snapshot testing in your PHPUnit tests.

It is a very basic trait and is only meant to snapshot JSON-encodable structures, not complex objects and such.

## Installation

```bash
composer require madewithlove/phpunit-snapshots
```

## Usage

### Using snapshots in tests

Simply call the assertion on any encodable result (the result of a function, a variable, etc.).
You can pass an identifier as second argument which will be used as title of the snapshot in the snapshot file.

```php
<?php
class MyTestCase extends \PHPUnit_Framework_TestCase
{
    use \Madewithlove\PhpunitSnapshots\SnapshotAssertions;
    
    public function testSomething()
    {
        $this->assertEqualsSnapshot($this->someComplexOperation());
        $this->assertEqualsSnapshot($this->someComplexOperation(), 'Compute something');
    }
}
```

This will generate a snapshot if we didn't have one for this test, else it will assert that the current results match the ones in the snapshot.

### Updating all snapshots

You can update all snapshots in your tests by running the following:

```bash
$ phpunit -d --update
```

## Testing

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
