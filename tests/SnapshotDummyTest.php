<?php

namespace Madewithlove\PhpunitSnapshots;

class SnapshotDummyTest extends TestCase
{
    public function testCanUseSnapshotTesting()
    {
        $this->assertEqualsSnapshot(['foo', 'bar']);
    }
}
