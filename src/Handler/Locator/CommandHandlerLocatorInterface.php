<?php
namespace PhpDDD\Command\Handler\Locator;

use PhpDDD\Command\CommandInterface;
use PhpDDD\Command\Exception\NoCommandHandlerRegisteredException;
use PhpDDD\Command\Handler\CommandHandlerInterface;

interface CommandHandlerLocatorInterface
{
    /**
     * Get the command handler specified for the command.
     *
     * @param CommandInterface $command
     *
     * @return CommandHandlerInterface
     * @throws NoCommandHandlerRegisteredException when no command handler is associated to the command
     */
    public function getCommandHandlerForCommand(CommandInterface $command);

    /**
     * @return CommandHandlerInterface[]
     */
    public function getRegisteredCommandHandlers();
}
