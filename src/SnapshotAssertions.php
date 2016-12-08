<?php

namespace Madewithlove\PhpunitSnapshots;

trait SnapshotAssertions
{
    /**
     * Asserts that a given output matches a registered snapshot
     * or update the latter if it doesn't exist yet.
     *
     * Passing an --update flag to PHPUnit will force updating
     * all snapshots
     *
     * @param mixed       $expected
     * @param string|null $identifier An additional identifier to append to the snapshot ID
     * @param string|null $message    A message to throw in case of error
     */
    protected function assertEqualsSnapshot($expected, $identifier = null, $message = null)
    {
        SnapshotsManager::setSuite($this);
        $snapshot = SnapshotsManager::upsertSnapshotContents($identifier, $expected);

        $this->assertEquals($snapshot, $expected, $message);
    }
}
