<?php

namespace Git;

use Git\Repository;

class File
{

    /**
     * @var string Real filesystem path of the file
     */
    protected $filename;
    /**
     * @var Repository the file repository
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

    public function isDirectory()
    {
        return is_dir($this->filename);
    }

    public function setContent($content)
    {
        $this->content = $content;
        $this->modified = true;
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
        if ($this->isModified()) {
            file_put_contents($this->getFullFilename(), $this->getContent());
            $this->modified = false;
            $this->repository->git('add "%s"', $this->filename);
            $this->repository->git('commit -m "%s"', $message);
        }
    }

    public function delete($message)
    {
        if (!$this->isNew()) {
            $this->repository->git('rm "%s"', $this->filename);
            $this->repository->git('commit -m "%s"', $message);
        }
    }

    public function move($target, $message)
    {
        if (!$this->isNew()) {
            $this->repository->git('mv "%s" "%s"', $this->filename, $target);
            $this->repository->git('commit -m "%s"', $message);
        } 
        $this->filename = $target;
    }

    public function getCommits($nbCommits = 10)
    {
        return $this->repository->getCommits($nbCommits, $this->filename);
    }
}
