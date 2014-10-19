<?php
namespace PhpDDD\Command\Bus;

use PhpDDD\Command\CommandInterface;
use PhpDDD\Command\Handler\CommandHandlerInterface;
use PhpDDD\Domain\AbstractAggregateRoot;

/**
 * Accept and process commands by passing them along to a matching command handler.
 */
interface CommandBusInterface
{
    /**
     * Dispatches the command $command to the proper CommandHandler
     *
     * @param CommandInterface $command
     *
     * @return AbstractAggregateRoot[]
     */
    public function dispatch(CommandInterface $command);

    /**
     * @return CommandHandlerInterface[]
     */
    public function getRegisteredCommandHandlers();
}
