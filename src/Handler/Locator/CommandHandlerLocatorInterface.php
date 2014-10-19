<?php
namespace PhpDDD\Command\Handler\Locator;

use PhpDDD\Command\CommandInterface;
use PhpDDD\Command\Handler\CommandHandlerInterface;

interface CommandHandlerLocatorInterface
{
    /**
     * @param CommandInterface $command
     *
     * @return CommandHandlerInterface
     */
    public function getCommandHandler(CommandInterface $command);

    /**
     * @return CommandHandlerInterface[]
     */
    public function getRegisteredCommandHandlers();
}
