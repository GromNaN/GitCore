<?php

namespace Git\Exception;

/**
 * Exception handled when trying to open directory witch is not a Git repository.
 *
 * @author    Thibault Duplessis <thibault.duplessis at gmail dot com>
 * @license   MIT License
 */
class InvalidGitRepositoryDirectoryException extends \InvalidArgumentException
{

}
