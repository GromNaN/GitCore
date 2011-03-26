<?php

namespace Git\Tests;

require_once __DIR__.'/TestCase.php';

use Git\Repository;
use Git\File;
use Git\Commit;
use Git\Tree;
use Git\Blob;
use Git\User;

class CommitTest extends TestCase
{

    public function testConstructor()
    {
        $commit = new Commit(self::$repository, 'fccd14eaa8d5f7654ce838becb6b47e34f4bcc43');

        $this->assertEquals('fccd14eaa8d5f7654ce838becb6b47e34f4bcc43', $commit->getHash());
        $this->assertEquals(array('5ff8ff94448ef3882daf4b9121b847d8540a065b'), $commit->getParentHashes());
        $this->assertTrue(current($commit->getParents()) instanceof Commit, 'Parents are Git\Commit');
        $this->assertEquals('884227673795f80c7ce03a79fabba733036401eb', $commit->getTreeHash());
        $this->assertTrue($commit->getTree() instanceof Tree, 'Tree is a Git\Tree');

        $author = new User('Cécile HONXA', 'cecile.honxa@author.local');
        $this->assertEquals($author, $commit->getAuthor(), 'Author infos are loaded');
        $this->assertTrue($commit->getAuthoredDate() instanceof \DateTime, 'AuthoredDate is a DateTime');

        $committer = new User('Lenny BARALAIR', 'lenny.baralair@commit.local');
        $this->assertEquals($committer, $commit->getCommitter(), 'Comitter infos are loaded');
        $this->assertTrue($commit->getCommittedDate() instanceof \DateTime, 'ComittedDate is a DateTime');

        $this->assertEquals('CREATE FILE3', $commit->getMessage(), 'Message received');
    }

    public function testMultipleParents()
    {
        $commit = new Commit(self::$repository, '0bdadcb19267edd56ee709cb1ec3339d711db584');

        $this->assertEquals(2, count($commit->getParentHashes()), 'Commit has 2 parent hashes');
        $this->assertEquals(2, count($parents = $commit->getParents()), 'Commit has 2 parents');

        $this->assertArrayHasKey('aafe1fa6e816e7ebcbf460ed03861815bd048e4c', $parents);
        $this->assertArrayHasKey('fccd14eaa8d5f7654ce838becb6b47e34f4bcc43', $parents);
    }

    public function testNullParents()
    {
        $commit = new Commit(self::$repository, '5ff8ff94448ef3882daf4b9121b847d8540a065b');

        $this->assertEquals(0, count($commit->getParentHashes()), 'Commit has no parent hash');
        $this->assertEquals(0, count($parents = $commit->getParents()), 'Commit has no parent');
    }

    public function testRawDiff()
    {
        $commit = new Commit(self::$repository, 'fccd14eaa8d5f7654ce838becb6b47e34f4bcc43');

        $this->assertEquals('4d0cbc9298c4d8c9877a84e42a5b8aac', md5($commit->getRawDiff()));

        $commit = new Commit(self::$repository, '5ff8ff94448ef3882daf4b9121b847d8540a065b');
        $this->assertEmpty($commit->getRawDiff());
    }

    public function testRepositoryLog()
    {
        $this->assertEquals(2, count(self::$repository->log(2)));

        $log = self::$repository->log();
        $this->assertEquals(6, count($log));

        foreach ($log as $key => $commit) {
            $this->assertEquals($key, $commit->getHash());
        }
    }

    public function testFileLog()
    {
        $file = new File(self::$repository, 'FILE1');

        $log = $file->log();

        $this->assertEquals(2, count($log), 'log has 2 entries');
        $this->assertArrayHasKey('aafe1fa6e816e7ebcbf460ed03861815bd048e4c', $log);
        $this->assertArrayHasKey('5ff8ff94448ef3882daf4b9121b847d8540a065b', $log);
    }

}
