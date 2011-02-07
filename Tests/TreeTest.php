<?php

namespace Git\Core\Tests;

require_once __DIR__.'/TestCase.php';

use Git\Core\Repository;
use Git\Core\Commit;
use Git\Core\Tree;
use Git\Core\Blob;

class TreeTest extends TestCase
{

    public function testConstructor()
    {
        $tree = self::$repository->getCurrentTree();

        $this->assertEquals('HEAD', $tree->getHash());
    }

    public function testChildren()
    {
        $tree = self::$repository->getCurrentTree();

        $children = $tree->getChildren();

        $this->assertEquals(3, count($children));

        foreach ($children as $hash => $child) {
            $this->assertEquals($hash, $child->getHash());
        }
        $this->assertEquals('FILE2', $children['d77231cee7bf500a9aa7ada4ca76dfd7dfef1d49']->getName(), 'Got the right file name');
        $this->assertInstanceOf('Git\Blob', $children['fc745af30d0909d443303f9997be3f674bf6c566'], 'It is a Blob');
        $this->assertInstanceOf('Git\Tree', $children['6c0fe566aeeba93df77b2e3d50c84fc51d6a14cf'], 'It is a Tree');
    }

    public function testSubTree()
    {
        $tree = new Tree(self::$repository, '6c0fe566aeeba93df77b2e3d50c84fc51d6a14cf');

        $this->assertEquals('6c0fe566aeeba93df77b2e3d50c84fc51d6a14cf', $tree->getHash());

        $children = $tree->getChildren();

        $this->assertEquals(1, count($children));
    }

}
