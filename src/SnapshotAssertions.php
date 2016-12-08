<?php

namespace Madewithlove\PhpunitSnapshots;

trait SnapshotAssertions
{
    /**
     * @var array
     */
    private $assertionsInTest = [];

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
        $snapshotPath = $this->getPathToSnapshot();
        $contents = $this->getSnapshotContents($snapshotPath);

        // If we already have a snapshot for this test, assert its contents
        // or update it if the --update flag was passed
        $methodName = $this->getAssertionIdentifier($identifier);
        if (!isset($contents[$methodName]) || $this->isUpdate()) {
            $contents[$methodName] = $expected;
            file_put_contents($snapshotPath, json_encode($contents, JSON_PRETTY_PRINT));
        }

        $this->assertEquals($contents[$methodName], $expected, $message);
    }

    ////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////// HELPERS ////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////

    /**
     * Get an unique identifier for this particular assertion.
     *
     * @param string|null $identifier
     *
     * @return string
     */
    private function getAssertionIdentifier($identifier)
    {
        $methodName = $this->getName();
        if (!isset($this->assertionsInTest[$methodName])) {
            $this->assertionsInTest[$methodName] = -1;
        }

        $name = $methodName.'-'.++$this->assertionsInTest[$methodName];
        $name = $identifier ? $name.': '.$identifier : $name;

        return $name;
    }

    /**
     * @return string
     */
    private function getPathToSnapshot()
    {
        $parent = debug_backtrace()[1]['file'];
        $snapshotPath = sprintf('%s/__snapshots__/%s.snap', dirname($parent), basename($parent));

        return $snapshotPath;
    }

    /**
     * @param string $snapshotPath
     *
     * @return array
     */
    private function getSnapshotContents($snapshotPath)
    {
        // If we're in update mode, purge the snapshots to
        // recreate them from scratch
        if ($this->isUpdate() && $this->assertionsInTest === [] && file_exists($snapshotPath)) {
            unlink($snapshotPath);
        }

        // If the folder doesn't yet exist, create it
        if (!is_dir(dirname($snapshotPath))) {
            mkdir(dirname($snapshotPath));
        }

        // If the file exists, fetch its contents, else
        // start from a new snapshot
        $contents = file_exists($snapshotPath)
            ? json_decode(file_get_contents($snapshotPath), true)
            : [];

        return $contents;
    }

    /**
     * @return bool
     */
    private function isUpdate()
    {
        return in_array('--update', $_SERVER['argv']);
    }
}
