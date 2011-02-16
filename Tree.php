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
 * A tree is a simple object that has a bunch of pointers to blobs and other trees.
 * It generally represents the contents of a directory or subdirectory.
 *
 * @author    Jérôme Tamarelle <jerome@tamarelle.net>
 */
class Tree extends Object
{

    /**
     * @var string
     */
    protected $name;

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
     * The tree name is the name of the directory.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * List the contents of a tree object
     *
     * @return array List of Tree and Blob objects
     */
    public function getObjects()
    {
        $output = $this->repository->git('ls-tree --full-name %s', escapeshellarg($this->hash));

        /**
         * The git ls-tree output looks like this:
         *
         * 100644 blob 1ffd74082f15970721362557f323d605c334ecb2	.gitignore
         * 100644 blob dd2630b84b948e7ed9a58f9828abb8d6dea2b2a9	LICENSE
         * 100644 blob 3ace88bc07b6dbc8eb0db22c4350fc45cf76a08c	bootstrap.php
         * 100644 blob 08fc9bebacbc01390265b19ced9336c2a3ad7ba7	prove.php
         * 040000 tree c409c5195393b6e8adb4a95b0bff5605d3d08372	src
         * 040000 tree 9296fb384d09a521c9540645669fe352b4af2772	test
         */
        $objects = array();
        foreach (\explode("\n", $output) as $line) {
            if (empty($line)) continue;

            $hash = \substr($line, 12, 40);
            $name = \substr($line, 53);

            switch (\substr($line, 7, 4)) {
                case 'tree':
                    $object = new Tree($this->repository, $hash, $name);
                    break;
                case 'blob':
                    $object = new Blob($this->repository, $hash, $name);
                    break;
                default:
                    throw new \Exception(\sprintf('Unable to determine git object type "%s"', $line));
            }

            $objects[$hash] = $object;
        }

        return $objects;
    }

}