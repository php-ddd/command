<?php
namespace PhpDDD\Command\Exception;

use RuntimeException;

class NoCommandHandlerRegisteredException extends RuntimeException implements CommandExceptionInterface
{
}
