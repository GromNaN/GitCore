<?php

namespace Git\Tests;

require_once __DIR__.'/../bootstrap.php';

use Git\Repository;
use Git\Commit;
use Git\Tree;
use Git\Blob;

class TreeTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $repo = new Repository(FIXTURES.'repo', DEBUG);
        $tree = $repo->getCurrentTree();

        $this->assertEquals('HEAD', $tree->getHash());
    }

    public function testChildren()
    {
        $repo = new Repository(FIXTURES.'repo', DEBUG);
        $tree = $repo->getCurrentTree();

        $children = $tree->getChildren();
        
        $this->assertEquals(3, count($children));

        foreach($children as $hash=>$child) {
            $this->assertEquals($hash, $child->getHash());
        }
        $this->assertEquals('FILE2', $children['d77231cee7bf500a9aa7ada4ca76dfd7dfef1d49']->getName(), 'Got the right file name');
        $this->assertInstanceOf('Git\Blob', $children['fc745af30d0909d443303f9997be3f674bf6c566'], 'It is a Blob');
        $this->assertInstanceOf('Git\Tree', $children['6c0fe566aeeba93df77b2e3d50c84fc51d6a14cf'], 'It is a Tree');
    }

    public function testSubTree()
    {
        $repo = new Repository(FIXTURES.'repo', DEBUG);
        $tree = new Tree($repo, '6c0fe566aeeba93df77b2e3d50c84fc51d6a14cf');

        $this->assertEquals('6c0fe566aeeba93df77b2e3d50c84fc51d6a14cf', $tree->getHash());

        $children = $tree->getChildren();

        $this->assertEquals(1, count($children));
    }
}
