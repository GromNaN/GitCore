<?php

namespace Git\Tests;

require_once __DIR__.'/TestCase.php';

use Git\Object;

class ObjectTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorWithInvalidHash()
    {
        new Object(self::$repository, '1234567');
    }
    
    public function testGetType()
    {
        $blob = new Object(self::$repository, 'd77231cee7bf500a9aa7ada4ca76dfd7dfef1d49');
        $tree = new Object(self::$repository, '6c0fe566aeeba93df77b2e3d50c84fc51d6a14cf');
        $commit = new Object(self::$repository, '74770c3b7ca5b6ab5400e291065ce48bb3e31785');
        
        $this->assertEquals($blob->getType(), 'blob');
        $this->assertEquals($tree->getType(), 'tree');
        $this->assertEquals($commit->getType(), 'commit');
    }
}

