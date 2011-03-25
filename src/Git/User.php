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
 * Author / Committer / Tagger
 *
 * @author    Jérôme Tamarelle <jerome@tamarelle.net>
 */
class User
{

    /**
     * The user name
     *
     * @var string
     */
    protected $name;

    /**
     * The user email
     *
     * @var string
     */
    protected $email;

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $email
     */
    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = (string) $email;
    }

    /**
     * Format user properties like "Jérôme <jerome@foo.com>"
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s <%s>', $this->name, $this->email);
    }

}
