<?php

/*
 * This file is part of the GitCore for PHP5.3
 *
 * (c) Jérôme Tamarelle <jerome@tamarelle.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Git;

/**
 * Every object consists of three things - a type, a size and content. There are
 * four different types of objects: "blob", "tree", "commit" and "tag".
 * Size is not implemented here, since I think is is useless.
 *
 * @link      http://book.git-scm.com/1_the_git_object_model.html
 * @author    Jérôme Tamarelle <jerome@tamarelle.net>
 */
use Git\Exception\GitInvalidArgumentException;

class Object
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
        $this->repository = $repository;

        if ( 'HEAD' != $hash
          && !\preg_match('/[0-9a-f]{40}/', $hash)) {
            throw new \InvalidArgumentException(sprintf('Invalid SHA1 hash "%s".', $hash));
        }

        $this->hash = $hash;
    }

    /**
     * Compare 2 object and determines if they are equals.
     *
     * @param Object $object
     * @return bool Whenever the given object is the same as the current object.
     */
    public function is($object)
    {
        return ($object instanceof Object) && ($this->getHash() === $object->getHash());
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
     * The SHA1 hash of this Git object
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }
}
