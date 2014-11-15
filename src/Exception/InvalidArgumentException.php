<?php
namespace PhpDDD\Command\Exception;

use InvalidArgumentException as BaseException;

/**
 * Exception thrown if an argument is not of the expected type.
 */
class InvalidArgumentException extends BaseException implements CommandExceptionInterface
{
}
