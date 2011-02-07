<?php

namespace Git\Core\Tests;

require_once __DIR__.'/TestCase.php';

use Git\Core\Repository;
use Git\Core\Commit;
use Git\Core\Blob;

class BlobTest extends TestCase
{

    public function testConstructor()
    {
        $blob = new Blob(self::$repository, 'fc745af30d0909d443303f9997be3f674bf6c566', 'FILE1');

        $this->assertEquals('fc745af30d0909d443303f9997be3f674bf6c566', $blob->getHash(), 'Hash');
        $this->assertEquals("FILE1+\n", $blob->getContents(), 'Contents');
        $this->assertEquals('FILE1', $blob->getName(), 'Name');
    }

}

