<?php

namespace Madewithlove\PhpunitSnapshots;

class SnapshotAssertionsTest extends \PHPUnit_Framework_TestCase
{
    use SnapshotAssertions;

    public function testCanUseSnapshotTesting()
    {
        $this->assertEqualsSnapshot(['foo', 'bar']);
        $this->assertEqualsSnapshot(['foo', 'baz']);
    }

    public function testCanEvenUseItInMultipleTests()
    {
        $this->assertEqualsSnapshot(['foo', 'bar'], 'some identifier');
    }
}
