<?php

namespace Git\Core;

/**
 * Every object consists of three things - a type, a size and content. There are
 * four different types of objects: "blob", "tree", "commit" and "tag".
 * Size is not implemented here, since I think is is useless.
 *
 * @link      http://book.git-scm.com/1_the_git_object_model.html
 * @author    Jérôme Tamarelle <jerome at tamarelle dot net>
 * @license   MIT License
 */
use Git\Exception\GitInvalidArgumentException;

abstract class Object
{

    /**
     * @var Git\Repository
     */
    protected $repository;

    /**
     * @var string SHA1 identifier of the object
     */
    protected $hash;

    /**
     * @var type Type of this object
     */
    protected $type;

    /**
     * Constructor.
     *
     * @param Repository $repository Repository of the object.
     * @param type $hash SHA1 identifier
     */
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
        if (!isset($this->type)) {
            $output = $this->repository->git('cat-file -t %s', $this->hash);
            $this->type = trim($output);
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
        if (!$repository) {
            throw new GitInvalidArgumentException('Git repository attribute is required.');
        }
        $this->repository = $repository;
    }

    /**
     * The SHA1 hash of this Git object
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     * @return bool true
     * @throw Git\Exception\GitInvalidArgumentException
     */
    protected function setHash($hash)
    {
        if (null !== $hash
          && 'HEAD' != $hash
          && !\preg_match('/[0-9a-f]{40}/', $hash)) {
            throw new GitInvalidArgumentException(sprintf('Invalid SHA1 hash "%s".', $hash));
        }

        $this->hash = $hash;
    }

}
