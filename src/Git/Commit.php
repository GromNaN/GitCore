<?php

class Commit
{
    const FORMAT = '"%H|%T|%an|%ae|%ad|%cn|%ce|%cd|%s"';
    const DATE_FORMAT = 'iso';

    /**
     *
     * @param string $output
     * @return array<Commit> 
     */
    public static function parse($output)
    {
        $commits = array();
        foreach (explode("\n", $output) as $line) {
            $infos = explode('|', $line);
            $commits[] = new Commit(array(
                        'id' => $infos[0],
                        'tree' => $infos[1],
                        'author' => array(
                            'name' => $infos[2],
                            'email' => $infos[3]
                        ),
                        'authored_date' => $infos[4],
                        'commiter' => array(
                            'name' => $infos[5],
                            'email' => $infos[6]
                        ),
                        'committed_date' => $infos[7],
                        'message' => $infos[8]
                    ));
        }
        return $commits;
    }

    protected $id;
    protected $tree;
    protected $author;
    protected $authored_date;
    protected $commiter;
    protected $committed_date;
    protected $message;

    /**
     * Constructor. 
     * 
     * @param array $data 
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ? : null;
        $this->tree = $data['tree'] ? : null;
        $this->author = $data['author'] ? : null;
        $this->authored_date = $data['authored_date'] ? new DateTime($data['authored_date']) : null;
        $this->commiter = $data['commiter'] ? : null;
        $this->committed_date = $data['committed_date'] ? new DateTime($data['committed_date']) : null;
        $this->message = $data['message'] ? : null;
    }

    /**
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getTree()
    {
        return $this->tree;
    }

    /**
     *
     * @return array
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     *
     * @return \DateTime
     */
    public function getAuthoredDate()
    {
        return $this->authored_date;
    }

    /**
     *
     * @return array
     */
    public function getCommiter()
    {
        return $this->commiter;
    }

    /**
     *
     * @return \DateTime
     */
    public function getCommittedDate()
    {
        return $this->committed_date;
    }

    /**
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
