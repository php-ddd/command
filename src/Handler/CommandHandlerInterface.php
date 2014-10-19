<?php
namespace PhpDDD\Command\Handler;

use PhpDDD\Command\CommandInterface;
use PhpDDD\Domain\AbstractAggregateRoot;

/**
 * A Command Handler aims to act on a specific command.
 * To respect the single responsibility principle, we force one command handler to handle one and only one command.
 */
interface CommandHandlerInterface
{

    /**
     * Tells whether the command given in argument can be handle by this handler or not.
     *
     * @param CommandInterface $command
     *
     * @return bool
     */
    public function acceptCommand(CommandInterface $command);

    /**
     * Process the command.
     *
     * @param CommandInterface $command
     *
     * @return AbstractAggregateRoot|null
     */
    public function handle(CommandInterface $command);
}
