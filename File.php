<?php

namespace Git\Core;

/**
 * File versioned in a Git repository.
 *
 * @author    Jérôme Tamarelle <jerome at tamarelle dot net>
 * @license   MIT License
 */
use Git\Core\Exception\InsufficientPermissionException;

class File extends \SplFileInfo
{

    /**
     * @var Repository The Git repository
     */
    protected $repository;

    /**
     * @var string Actual contents of the file
     */
    protected $contents;

    /**
     * Instanciate a new file
     *
     * @param Repository $repository The Git repository hosting the file.
     * @param string $file_name Opened file full path name
     */
    public function __construct(Repository $repository, $file_name)
    {
        parent::__construct($repository->getDir().'/'.$file_name);
        $this->repository = $repository;
        $this->setInfoClass(__CLASS__);
    }

    /**
     * Path of the file inside the repository
     *
     * @return string
     */
    public function getRelativePathname()
    {
        return substr($this->getPathname(), strlen($this->repository->getDir()) + 1);
    }

    /**
     * Does the file already exists on the filesystem ?
     *
     * @return bool
     */
    public function exists()
    {
        return \file_exists($this->getPathname());
    }

    /**
     * Replace the contents of the file by the given content.
     * You must use File::save() method to effectively overwrite the file.
     *
     * @param string $contents
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    /**
     * Retreive the contents of the file.
     *
     * @param bool $noCache Force to load the contents from the file.
     * @return string
     */
    public function getContents($noCache = false)
    {
        if (null === $this->contents || $noCache) {
            if (!$this->exists()) {
                return '';
            } elseif ($this->isReadable()) {
                return \file_get_contents($this->getPathname());
            } else {
                throw new InsufficientPermissionException(sprintf('File "%s" is not readable.', $this->getPathname()));
            }
        }

        return $this->contents;
    }

    /**
     * Save the file contents.
     */
    public function save()
    {
        if (!$this->exists()) {
            mkdir($this->getPath(), 0777, true); // @todo Configurable default chmod
            if (!\is_writable($this->getPath())) {
                throw new InsufficientPermissionException(sprintf('Directory "%s" is not writable.', $this->getPath()));
            }
        } else {
            if (!$this->isWritable()) {
                throw new InsufficientPermissionException(sprintf('File "%s" is not writable.', $this->getPathname()));
            }
        }

        \file_put_contents($this->getPathname(), $this->contents);
        $this->add();
    }

    /**
     * Get the content of the file for a given version
     *
     * @param string $hash Hash of the version
     * return string Historical content of the file
     */
    public function getHistoricalContent($hash)
    {
        $content = $this->repository->git('cat-file -p %s:%s', escapeshellarg($hash), escapeshellarg($this->getFilename()));
    }

    /**
     * Set the file to be committed at the next commit.
     */
    public function add()
    {
        $this->repository->git('add %s', escapeshellarg($this->getRelativePathname())
        );
    }

    /**
     * Set the file to be removed at the next commit.
     */
    public function delete()
    {
        $this->repository->git('rm %s', escapeshellarg($this->getRelativePathname())
        );
    }

    /**
     * Commit changes to the git repository.
     *
     * @example $file->commit('Bug fix', 'Jérôme <jerome@foo.com>');
     *
     * @param string $message Commit message, cannot be empty.
     * @param User|string $author (Optional) Author of the changes
     */
    public function commit($message, $author = null)
    {
        if (empty($message)) {
            throw new \InvalidArgumentException(sprintf('Git commit message "%s" is not valid.', $message));
        }

        if (null === $author) {
            $this->repository->git('commit --allow-empty --no-verify --message=%s -- %s', escapeshellarg($message), escapeshellarg($this->getRelativePathname())
            );
        } else {
            $this->repository->git('commit --allow-empty --no-verify --message=%s --author=%s -- %s', escapeshellarg($message), escapeshellarg(strval($author)), escapeshellarg($this->getRelativePathname())
            );
        }
    }

    /**
     * Get last commits on the file.
     *
     * @param int $nbCommits Number of log entries to get
     * @return array Commit objects representing last modifications.
     */
    public function log($nbCommits = 10)
    {
        $output = $this->repository->git('log -n %d %s -- %s', (int) $nbCommits, Commit::FORMAT, escapeshellarg($this->getRelativePathname()));

        return Commit::parse($this->repository, $output);
    }

    /**
     * Calculate the difference between 2 versions
     *
     * @param int $context Number of context lines around changes
     * @param string $hash1 Hash of the first version
     * @param string $hash2 Hash of the second version
     * @return string String response of the git diff command
     */
    public function diff($context = 2, $hash1 = null, $hash2 = null)
    {
        if (null === $hash1) {
            $output = $this->repository->git('diff -U%d %s', (int) $context, escapeshellarg($this->getFilename())
            );
        } elseif (null === $hash2) {
            $output = $this->repository->git('diff -U%d %s -- %s', (int) $context, escapeshellarg($hash1), escapeshellarg($this->getFilename())
            );
        } else {
            $output = $this->repository->git('diff -U%d %s %s -- %s', (int) $context, escapeshellarg($hash1), escapeshellarg($hash2), escapeshellarg($this->getFilename())
            );
        }

        return $output;
    }

}
