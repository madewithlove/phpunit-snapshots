<?php

namespace Madewithlove\PhpunitSnapshots;

class SnapshotsManagerTest extends TestCase
{
    /**
     * @var array
     */
    protected $arguments = [];

    public function setUp()
    {
        $this->arguments = $_SERVER['argv'];
        $this->assertEqualsSnapshot('foobar');
    }

    public function tearDown()
    {
        $_SERVER['argv'] = $this->arguments;
    }

    ////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////// TESTS /////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////

    public function testCanCheckIfIsUpdate()
    {
        $_SERVER['argv'] = [];
        $this->assertFalse(SnapshotsManager::isUpdate());

        $_SERVER['argv'][] = '--update';
        $this->assertTrue(SnapshotsManager::isUpdate());
    }

    public function testCanGetTestSuitePath()
    {
        $this->assertEquals(__FILE__, SnapshotsManager::getTestSuitePath());
    }

    public function testCanGetPathToSnapshot()
    {
        $this->assertEquals(__DIR__.'/__snapshots__/SnapshotsManagerTest.php.snap', SnapshotsManager::getSnapshotPath());
    }

    public function testCanGetAssertionIdentifier()
    {
        $this->assertEquals('testCanGetAssertionIdentifier-1', SnapshotsManager::getAssertionIdentifier());
        $this->assertEquals('testCanGetAssertionIdentifier-2: foobar', SnapshotsManager::getAssertionIdentifier('foobar'));
    }

    public function testCanGetSnapshotContents()
    {
        $this->assertArrayHasKey('testCanGetAssertionIdentifier-0', SnapshotsManager::getSnapshotContents());
        $this->assertArrayHasKey('testCanGetAssertionIdentifier-0', SnapshotsManager::getSnapshotContents(__DIR__.'/__snapshots__/SnapshotsManagerTest.php.snap'));
    }
}
