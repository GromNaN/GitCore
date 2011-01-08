<?php

namespace Git;
/**
 * Git commit.
 * Do not instanciate directly, use Git\Commit::parse($output) instead.
 *
 * @link      http://github.com/GromNaN/php-git-repo
 * @version   2.0.0
 * @author    Jérôme Tamarelle <http://jerome.tamarelle.net/>
 * @license   MIT License
 */
class Commit
{
    /**
     * Log output format that can be parsed by Commit::parse method.
     */
    const FORMAT = '"%H|%T|%an|%ae|%ad|%cn|%ce|%cd|%s"';

    /**
     * Git date format to be parsed by \DateTime class.
     */
    const DATE_FORMAT = 'iso';

    /**
     * Parse the response of a git-log command.
     * 
     * @param string $output
     * @return array<Commit> 
     */
    public static function parse($output)
    {
        $commits = array();

        if (!empty($output)) {
            foreach (explode("\n", $output) as $line) {
                $infos = explode('|', $line);
                $commit = new Commit();
                $commit->id = $infos[0];
                $commit->tree = $infos[1];
                $commit->author_name = $infos[2];
                $commit->author_email = $infos[3];
                $commit->authored_date = new \DateTime($infos[4]);
                $commit->commiter_name = $infos[5];
                $commit->commiter_email = $infos[6];
                $commit->committed_date = new \DateTime($infos[7]);
                $commit->message = $infos[8];
                $commits[] = $commit;
            }
        }

        return $commits;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTree()
    {
        return $this->tree;
    }

    /**
     * @return string
     */
    public function getAuthorName()
    {
        return $this->author_name;
    }

    /**
     * @return string
     */
    public function getAuthorEmail()
    {
        return $this->author_email;
    }

    /**
     * @return \DateTime
     */
    public function getAuthoredDate()
    {
        return $this->authored_date;
    }

    /**
     * @return string
     */
    public function getCommiterName()
    {
        return $this->commiter_name;
    }

    /**
     * @return string
     */
    public function getCommiterEmail()
    {
        return $this->commiter_email;
    }

    /**
     * @return \DateTime
     */
    public function getCommittedDate()
    {
        return $this->committed_date;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

}
