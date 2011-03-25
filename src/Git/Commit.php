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
 * Git commit.
 *
 * @author    Jérôme Tamarelle <jerome@tamarelle.net>
 */

class Commit extends Object
{
    /**
     * git-log output format that can be parsed by Commit::parse method.
     */
    const FORMAT = '--date=iso --pretty=format:"%H|%T|%P|%an|%ae|%ad|%cn|%ce|%cd|%s"';

    /**
     * Parse the response of a git-log command.
     *
     * @param Repository $repository The project repository
     * @param string $output
     * @return array<Commit>
     */
    public static function parse(Repository $repository, $output)
    {
        $commits = array();

        if (!empty($output)) {
            foreach (explode("\n", $output) as $line) {
                $commit = new Commit($repository);
                $commit->init($line);
                $commits[$commit->getHash()] = $commit;
            }
        }

        return $commits;
    }

    protected $treeHash;
    protected $tree;
    protected $parentHashes;
    protected $parents;
    protected $author;
    protected $authoredDate;
    protected $committer;
    protected $committedDate;
    protected $message;

    public function __construct(Repository $repository, $hash = null)
    {
        parent::__construct($repository, $hash);

        // Retreive details only if a hash is given
        if (null !== $hash) {
            $line = $this->repository->git('show -s %s %s',
                            Commit::FORMAT,
                            escapeshellarg($hash));
            $this->init($line);
        }
    }

    /**
     * Init commit values from git command output line.
     * The format must follow Commit::FORMAT
     *
     * @param string $line
     */
    protected function init($line)
    {
        $infos = explode('|', $line);

        $this->hash = $infos[0];
        $this->treeHash = $infos[1];
        $this->parentHashes = empty($infos[2]) ? array() : explode(' ', $infos[2]);
        $this->author = new User($infos[3], $infos[4]);
        $this->authoredDate = new \DateTime($infos[5]);
        $this->committer = new User($infos[6], $infos[7]);
        $this->committedDate = new \DateTime($infos[8]);
        $this->message = $infos[9];
    }

    /**
     * The SHA1 hash of a tree object.
     *
     * @return sha1
     */
    public function getTreeHash()
    {
        return $this->treeHash;
    }

    /**
     * The tree object, representing the contents of a directory
     * at a certain point in time.
     *
     * @return Git\Core\Tree
     */
    public function getTree()
    {
        if (null === $this->tree) {
            $this->tree = new Tree($this->repository, $this->treeHash);
        }

        return $this->tree;
    }

    /**
     * The SHA1 name of some number of commits which represent the immediately
     * previous step(s) in the history of the project. Merge commits may have
     * more than one. A commit with no parents is called a "root" commit, and
     * represents the initial revision of a project.
     *
     * @return array<string>
     */
    public function getParentHashes()
    {
        return $this->parentHashes;
    }

    /**
     * Commits which represent the immediately previous step(s) in the history
     * of the project. Merge commits may have more than one. A commit with no
     * parents is called a "root" commit, and represents the initial revision
     * of a project.
     *
     * @return array<Commit>
     */
    public function getParents()
    {
        if (null === $this->parents) {
            $this->parents = array();
            foreach ($this->parentHashes as $parentHash) {
                $this->parents[$parentHash] = new Commit($this->repository, $parentHash);
            }
        }

        return $this->parents;
    }

    /**
     * @return Git\Core\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return \DateTime
     */
    public function getAuthoredDate()
    {
        return $this->authoredDate;
    }

    /**
     * @return Git\Core\User
     */
    public function getCommitter()
    {
        return $this->committer;
    }

    /**
     * @return \DateTime
     */
    public function getCommittedDate()
    {
        return $this->committedDate;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Reads the diff made by the commit.
     *
     * @return string
     */
    public function getRawDiff()
    {
        return $this->repository->git('git diff-tree -p %s',
                escapeshellarg($this->hash));
    }
}
