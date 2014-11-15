<?php

namespace PhpDDD\Command\Handler;

use InvalidArgumentException;
use PhpDDD\Command\CommandInterface;

/**
 * generic implementation of CommandHandlerInterface
 */
abstract class AbstractCommandHandler implements CommandHandlerInterface
{
    /**
     * @inheritdoc
     */
    public function acceptCommand(CommandInterface $command)
    {
        $allowedCommandClassName = $this->getAllowedCommandClassName();

        return $command instanceof $allowedCommandClassName;
    }

    /**
     * @return string the fully qualified name of the command class that this handler can accept.
     */
    abstract public function getAllowedCommandClassName();

    /**
     * @param CommandInterface $command
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function handle(CommandInterface $command)
    {
        if (!$this->acceptCommand($command)) {
            throw new InvalidArgumentException(
                sprintf(
                    'The command must be an instance of "%s". "%s" given.',
                    $this->getAllowedCommandClassName(),
                    get_class($command)
                )
            );
        }

        return $this->execute($command);
    }

    /**
     * Handle the command assuming that this command can be handled by the current handler.
     *
     * @param CommandInterface $command
     *
     * @return mixed
     */
    abstract protected function execute(CommandInterface $command);
}
