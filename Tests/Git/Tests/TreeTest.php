<?php

namespace Git\Tests;

require_once __DIR__.'/TestCase.php';

use Git\Repository;
use Git\Commit;
use Git\Tree;
use Git\Blob;

class TreeTest extends TestCase
{
    protected $type = 'tree';

    public function testConstructor()
    {
        $tree = new Tree(self::$repository, '6eaa2ddcc46ec7bc4f5c9445d95835966d9feab9');

        $this->assertEquals('6eaa2ddcc46ec7bc4f5c9445d95835966d9feab9', $tree->getHash());
        $this->assertEquals(self::$repository, $tree->getRepository(), 'Repository');
    }

    public function testGetObjects()
    {
        $tree = new Tree(self::$repository, '6eaa2ddcc46ec7bc4f5c9445d95835966d9feab9');

        $objects = $tree->getObjects();

        $this->assertEquals(3, count($objects));

        foreach ($objects as $hash => $object) {
            $this->assertEquals($hash, $object->getHash(), 'Associative array');
        }

        $this->assertEquals('FILE2', $objects['d77231cee7bf500a9aa7ada4ca76dfd7dfef1d49']->getName(), 'Got the right file name');
        $this->assertTrue($objects['fc745af30d0909d443303f9997be3f674bf6c566'] instanceof Blob, 'It is a Blob');
        $this->assertTrue($objects['6c0fe566aeeba93df77b2e3d50c84fc51d6a14cf'] instanceof Tree, 'It is a Tree');
    }

    public function testSubTree()
    {
        $tree = new Tree(self::$repository, '6c0fe566aeeba93df77b2e3d50c84fc51d6a14cf');

        $this->assertEquals('6c0fe566aeeba93df77b2e3d50c84fc51d6a14cf', $tree->getHash());

        $objects = $tree->getObjects();

        $this->assertEquals(1, count($objects));
    }

}
