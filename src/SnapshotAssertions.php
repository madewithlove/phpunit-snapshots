<?php
namespace Madewithlove\PhpunitSnapshot;

trait SnapshotAssertions
{
    /**
     * Asserts that a given output matches a registered snapshot
     * or update the latter if it doesn't exist yet
     *
     * Passing an --update flag to PHPUnit will force updating
     * all snapshots
     *
     * @param mixed       $expected
     * @param string|null $message
     */
    protected function assertEqualsSnapshot($expected, $message = null)
    {
        $methodName = $this->getName();
        $parent = debug_backtrace()[0]['file'];
        $snapshotPath = sprintf('%s/__snapshots__/%s.snap', dirname($parent), basename($parent));

        // If the folder doesn't yet exist, create it
        if (!is_dir(dirname($snapshotPath))) {
            mkdir(dirname($snapshotPath));
        }

        // If the file exists, fetch its contents, else
        // start from a new snapshot
        $contents = file_exists($snapshotPath)
            ? json_decode(file_get_contents($snapshotPath), true)
            : [];

        // If we already have a snapshot for this test, assert its contents
        // or update it if the --update flag was passed
        $isUpdate = in_array('--update', $_SERVER['argv']);
        if (!isset($contents[$methodName]) || $isUpdate) {
            $contents[$methodName] = $expected;
            file_put_contents($snapshotPath, json_encode($contents, JSON_PRETTY_PRINT));
        }

        $this->assertEquals($contents[$methodName], $expected, $message);
    }
}