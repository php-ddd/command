<?php
namespace PhpDDD\Command\Handler;

use PhpDDD\Command\CommandInterface;

/**
 * A Command Handler aims to act on a specific command.
 * To respect the single responsibility principle, we force one command handler to handle one and only one command.
 */
interface CommandHandlerInterface
{

    /**
     * Tells whether the command given in argument can be handle by this handler or not.
     *
     * @param string $commandClassName
     *
     * @return bool
     */
    public function supports($commandClassName);

    /**
     * Process the command.
     *
     * @param CommandInterface $command
     *
     * @return mixed
     */
    public function handle(CommandInterface $command);
}
