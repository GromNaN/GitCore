<?php

namespace Git\Exception;

/**
 * Exception handled when trying to open directory witch is not a Git repository.
 *
 * @author    Jérôme Tamarelle <jerome at tamarelle dot net>
 * @license   MIT License
 */
class InsufficientPermissionException extends \ErrorException
{

}
