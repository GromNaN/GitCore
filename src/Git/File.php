<?php

namespace Git;

/**
 * File versioned in a Git repository.
 *
 * @link      http://github.com/GromNaN/php-git-repo
 * @version   2.0.0
 * @author    Jérôme Tamarelle <http://jerome.tamarelle.net/>
 * @license   MIT License
 */
class File
{

    /**
     * @var string Real filesystem path of the file
     */
    protected $filename;
    /**
     * @var Repository The Git repository
     */
    protected $repository;
    /**
     * @var string File content
     */
    protected $content;
    /**
     * @var boolean Does the file content has been modified
     */
    protected $modified = false;

    /**
     * Instanciate a new file 
     * 
     * @param  Repository  The Git repository hosting the file.
     * @param  
     */
    public function __construct(Repository $repository, $filename)
    {
        $this->repository = $repository;
        $this->filename = $filename;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getFullFilename()
    {
        return $this->repository->getDir().'/'.$this->getFilename();
    }

    public function isModified()
    {
        return $this->modified;
    }

    public function isNew()
    {
        return!file_exists($this->getFullFilename());
    }

    public function isDir()
    {
        return is_dir($this->filename);
    }

    public function setContent($content)
    {
        if ($content != $this->getContent()) {
            $this->content = $content;
            $this->modified = true;
        }
    }

    public function getContent()
    {
        if (!$this->content) {
            if ($this->isNew()) {
                $this->content = '';
            } else {
                $this->content = file_get_contents($this->getFullFilename());
            }
        }
        return $this->content;
    }

    public function save($message)
    {
        if ($this->isModified() || $this->isNew()) {
            file_put_contents($this->getFullFilename(), $this->getContent());
            $this->modified = false;
            $this->repository->git('add %s', escapeshellarg($this->filename));
            $this->commit($message);
        }
    }

    public function delete($message)
    {
        if (!$this->isNew()) {
            $this->repository->git('rm "%s"', $this->filename);
            $this->commit($message);
        }
    }

    public function move($target, $message)
    {
        if (!$this->isNew()) {
            $this->repository->git('mv %s %s', escapeshellarg($this->filename), escapeshellarg($target));
            $this->commit($message);
        }
        $this->filename = $target;
    }

    public function commit($message)
    {
        if (empty($message)) {
            throw new Exception('Commit message cannot empty.');
        }
        $this->repository->git('commit %s -m %s', escapeshellarg($this->filename), escapeshellarg($message));
    }

    public function getCommits($nbCommits = 10)
    {
        $output = $this->repository->git('log -n %d --date=%s --format=format:%s -- %s', $nbCommits, Commit::DATE_FORMAT, Commit::FORMAT, escapeshellarg($this->filename));

        return Commit::parse($output);
    }

}
