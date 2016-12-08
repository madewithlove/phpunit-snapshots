<?php

namespace Madewithlove\PhpunitSnapshots;

use RuntimeException;

trait SnapshotAssertions
{
    /**
     * @var array
     */
    protected static $assertionsInTest = [];

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
        $identifier = $this->getAssertionIdentifier($identifier);
        $snapshotPath = $this->getPathToSnapshot();
        $contents = $this->getSnapshotContents($snapshotPath);

        // If we already have a snapshot for this test, assert its contents
        // or update it if the --update flag was passed
        if (!isset($contents[$identifier]) || $this->isUpdate()) {
            $contents[$identifier] = $expected;
            file_put_contents($snapshotPath, json_encode($contents, JSON_PRETTY_PRINT));
        }

        $this->assertEquals($contents[$identifier], $expected, $message);
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
        // Keep a registry of how many assertions were run
        // in this test suite, and in this test
        $className = get_class($this);
        $methodName = $this->getName();
        static::$assertionsInTest[$className][$methodName] = isset(static::$assertionsInTest[$className][$methodName])
            ? static::$assertionsInTest[$className][$methodName]
            : -1;

        $name = $methodName.'-'.++static::$assertionsInTest[$className][$methodName];
        $name = $identifier ? $name.': '.$identifier : $name;

        return $name;
    }

    /**
     * @return string
     */
    private function getPathToSnapshot()
    {
        $parent = $this->getTestSuiteAttribute('file');
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
        // If we're in update mode, purge the snapshots to recreate
        // them from scratch if this is the first assertion
        // of this test suite
        $className = get_class($this);
        $assertions = array_values(static::$assertionsInTest[$className]);
        if ($this->isUpdate() && $assertions === [0] && file_exists($snapshotPath)) {
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
     * @param string $attribute
     *
     * @return mixed
     */
    private function getTestSuiteAttribute($attribute)
    {
        $backtrace = debug_backtrace();
        foreach ($backtrace as $entry) {
            if (strpos($entry['file'], 'vendor') === false && $entry['file'] !== __FILE__) {
                return $entry[$attribute];
            }
        }

        throw new RuntimeException('Could not figure out proper path for snapshot');
    }

    /**
     * @return bool
     */
    private function isUpdate()
    {
        return in_array('--update', $_SERVER['argv']);
    }
}
