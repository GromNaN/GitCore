<?php

namespace Git\Tests;

use Git;

require_once __DIR__.'/TestCase.php';

use Git\Repository;
use Git\Commit;
use Git\Blob;

class RepositoryTest extends TestCase
{
	/**
	 * @expectedException Git\Exception\GitRuntimeException
	 */
    public function testCreate()
    {
        Repository::create('/dev/null');
    }

	/**
	 * @expectedException Git\Exception\InvalidGitRepositoryDirectoryException
	 */
    public function testConstructorInvalidDir()
    {
    	new Repository('/dev/null');
    }
    
    public function testGetBranches()
    {
    	$branches = self::$repository->getBranches();
    	$this->assertEquals($branches, array('master', 'parallel'));
    }
    
    public function testGetCurrentBranch()
    {
    	$branch = self::$repository->getCurrentBranch();
    	$this->assertEquals($branch, 'master');
    }
    
    public function testHasBranch()
    {
    	$this->assertTrue(self::$repository->hasBranch('master'));
    	$this->assertTrue(self::$repository->hasBranch('parallel'));
    	$this->assertfalse(self::$repository->hasBranch('foo'));
    }
    
    /**
     * @expectedException Git\Exception\GitRuntimeException
     */
    public function testInvalidCommand()
    {
    	self::$repository->git('wtf');
    }
    
    
}

