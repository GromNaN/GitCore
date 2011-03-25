<?php

/*
 * This file is part of the GitCore for PHP5.3
 *
 * (c) Jérôme Tamarelle <jerome@tamarelle.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Git\Core;

/**
 * A "blob" object is nothing but a chunk of binary data. It doesn't refer to
 * anything else or have attributes of any kind, not even a file name.
 *
 * Since the blob is entirely defined by its data, if two files in a directory
 * tree (or in multiple different versions of the repository) have the same
 * contents, they will share the same blob object. The object is totally
 * independent of its location in the directory tree, and renaming a file does
 * not change the object that file is associated with.
 *
 * @author    Jérôme Tamarelle <jerome@tamarelle.net>
 *
 * @todo Implement streamContents methods to send large files directly to output
 */
class Blob extends Object
{
    protected $type = 'blob';

    protected $contents = null;

    /**
     * Constructor.
     *
     * @param Git\Repository $repository
     * @param sha1 $hash
     */
    public function __construct(Repository $repository, $hash = null, $name = null)
    {
        parent::__construct($repository, $hash);
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getContents()
    {
        if (null === $this->contents) {
            $this->contents = $this->repository->git('cat-file blob %s', escapeshellarg($this->hash));
        }

        return $this->contents;
    }

    public function getName()
    {
        return $this->name;
    }

}
