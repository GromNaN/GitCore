<?php

namespace Git\Tests;

require_once __DIR__.'/../bootstrap.php';

use Git\Repository;
use Git\Commit;
use Git\Blob;
use Git\GitObject;

class BlobTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $repo = new Repository(FIXTURES.'repo', DEBUG);
        $blob = new Blob($repo, 'fc745af30d0909d443303f9997be3f674bf6c566', 'FILE1');

        $this->assertEquals('fc745af30d0909d443303f9997be3f674bf6c566', $blob->getHash(), 'Hash');
        $this->assertEquals("FILE1+\n", $blob->getContents(), 'Contents');
        $this->assertEquals('FILE1', $blob->getName(), 'Name');
    }
}
