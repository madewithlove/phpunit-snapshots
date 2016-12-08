<?php

namespace Madewithlove\PhpunitSnapshots;

use PHPUnit_Framework_TestCase;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    use SnapshotAssertions;
}
