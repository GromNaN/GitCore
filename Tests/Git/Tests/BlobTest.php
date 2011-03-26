<?php

namespace Git\Tests;

require_once __DIR__.'/TestCase.php';

use Git\Repository;
use Git\Commit;
use Git\Blob;

class BlobTest extends TestCase
{

    public function testConstructor()
    {
        $blob = new Blob(self::$repository, 'fc745af30d0909d443303f9997be3f674bf6c566', 'FILE1');

        $this->assertEquals('fc745af30d0909d443303f9997be3f674bf6c566', $blob->getHash(), 'Hash');
        $this->assertEquals("FILE1+\n", $blob->getContents(), 'Contents');
        $this->assertEquals('FILE1', $blob->getName(), 'Name');
        $this->assertEquals(self::$repository, $blob->getRepository(), 'Repository');
    }

    public function testIs()
    {
        $blob1 = new Blob(self::$repository, 'fc745af30d0909d443303f9997be3f674bf6c566', 'FILE1');
        $blob1->getContents();
        $blob2 = new Blob(self::$repository, 'fc745af30d0909d443303f9997be3f674bf6c566', 'FILE1');

        $this->assertFalse($blob1 === $blob2, 'Objects are differents');
        $this->assertTrue($blob1->is($blob2), 'But they are equals');
    }

}

