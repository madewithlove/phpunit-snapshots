<?php

namespace Madewithlove\PhpunitSnapshots;

class SnapshotAssertionsTest extends TestCase
{
    public function testCanGetTestSuitePath()
    {
        $this->assertEqualsSnapshot(['foo', 'bar']);
        $this->assertEqualsSnapshot(['foo', 'baz']);
    }

    public function testCanUseItInMultipleTests()
    {
        $this->assertEqualsSnapshot(['foo', 'bar'], 'some identifier');
    }
}
