<?php

namespace Git\Core\Tests;

require_once __DIR__.'/TestCase.php';

use Git\Core\Repository;
use Git\Core\File;
use Git\Core\Commit;

class CommitTest extends TestCase
{

    public function testConstructor()
    {
        $commit = new Commit($this->repository, 'f7e63c516e73451f915e904d27171c17ebc303ba');

        $this->assertEquals('f7e63c516e73451f915e904d27171c17ebc303ba', $commit->getHash());
        $this->assertEquals(array('94684d5254c09b915149b43d16e41843d4fb0905'), $commit->getParentHashes());
        $this->assertInstanceOf('Git\Commit', current($commit->getParents()));
        $this->assertEquals('884227673795f80c7ce03a79fabba733036401eb', $commit->getTreeHash());
        $this->assertInstanceOf('Git\Tree', $commit->getTree(), 'Tree is a Git\Tree');
        $this->assertInstanceOf('Git\User', $commit->getAuthor(), 'Author a Git\User');
        $this->assertInstanceOf('DateTime', $commit->getAuthoredDate(), 'AuthoredDate is a DateTime');
        $this->assertInstanceOf('Git\User', $commit->getCommitter(), 'Comitter a Git\User');
        $this->assertInstanceOf('DateTime', $commit->getCommittedDate(), 'ComittedDate is a DateTime');
    }

    public function testMultipleParents()
    {
        $commit = new Commit($this->repository, '10bdf83cda44ca5503cfb8d710506d2e5e40832d');

        $this->assertEquals(2, count($commit->getParentHashes()), 'Commit has 2 parent hashes');
        $this->assertEquals(2, count($parents = $commit->getParents()), 'Commit has 2 parents');

        foreach ($parents as $hash => $parent) {
            $this->assertEquals($hash, $parent->getHash());
        }
    }

    public function testNullParents()
    {
        $commit = new Commit($this->repository, '94684d5254c09b915149b43d16e41843d4fb0905');

        $this->assertEquals(0, count($commit->getParentHashes()), 'Commit has no parent hash');
        $this->assertEquals(0, count($parents = $commit->getParents()), 'Commit has no parent');
    }

    public function testRepositoryLog()
    {
        $this->assertEquals(2, count($this->repository->log(2)));

        $log = $this->repository->log();
        $this->assertEquals(6, count($log));

        foreach ($log as $key => $commit) {
            $this->assertEquals($key, $commit->getHash());
        }
    }

    public function testFileLog()
    {
        $file = new File($this->repository, 'FILE1');

        $log = $file->log();

        $this->assertEquals(2, count($log), 'log has 2 entries');
        $this->assertArrayHasKey('559df3c3e37f1e97f83e1d1539d692ace411d6f8', $log);
        $this->assertArrayHasKey('94684d5254c09b915149b43d16e41843d4fb0905', $log);
    }

}
