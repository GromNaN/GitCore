<?php

namespace Git\Exception;

/**
 * Exception handled when trying to open directory witch is not a Git repository.
 *
 * @link      http://github.com/GromNaN/php-git-repo
 * @version   2.0.0
 * @author    Thibault Duplessis <thibault.duplessis at gmail dot com>
 * @license   MIT License
 */
class InvalidGitRepositoryDirectoryException extends \InvalidArgumentException
{

}
