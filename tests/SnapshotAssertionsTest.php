<?php
namespace Madewithlove\PhpunitSnapshot;

class SnapshotAssertionsTest extends \PHPUnit_Framework_TestCase
{
    use SnapshotAssertions;

    public function testCanUseSnapshotTesting()
    {
        $this->assertEqualsSnapshot(['foo', 'bar']);
        $this->assertEqualsSnapshot(['foo', 'baz']);
    }
}
