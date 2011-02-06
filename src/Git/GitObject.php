<?php

namespace Git;

/**
 * @link http://book.git-scm.com/1_the_git_object_model.html
 */

use Git\Exception\GitInvalidArgumentException;

abstract class GitObject
{
    /**
     * @var Git\Repository
     */
    protected $repository;

    /**
     * @var sha1
     */
    protected $hash;

    protected $type;

    public function __construct(Repository $repository, $hash)
    {
        $this->setRepository($repository);
        $this->setHash($hash);
    }

    /**
     * Returns the type of this git object, as reported by git cat-file -t
     *
     * @return string
     */
    public function getType()
    {
        if (!isset($this->type))
		{
			$this->type = trim($this->repository->git('cat-file -t %s',
                    $this->hash));
		}
		return $this->type;
    }

    /**
     * @return Git\Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param Git\Repository $repository
     * @throw Git\Exception\GitInvalidArgumentException
     */
    public function setRepository(Repository $repository)
    {
        if(!$repository) {
            throw new GitInvalidArgumentException('Git repository attribute is required.');
        }
        $this->repository = $repository;
    }

    /**
     * The SHA1 hash of this Git object
     *
     * @return sha1
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param sha1 $hash
     * @return bool true
     * @throw Git\Exception\GitInvalidArgumentException
     */
    protected function setHash($hash)
    {
        if(null !== $hash
          && 'HEAD' != $hash
          && !\preg_match('/[0-9a-f]{40}/', $hash)) {
            throw new GitInvalidArgumentException(sprintf('Invalid SHA1 hash "%s".', $hash));
        }

        $this->hash = $hash;
    }
}
