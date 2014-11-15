<?php
namespace PhpDDD\Command\Handler\Locator;

use PhpDDD\Command\CommandInterface;
use PhpDDD\Command\Exception\InvalidArgumentException;
use PhpDDD\Command\Handler\CommandHandlerInterface;
use PhpDDD\Command\Utils\ClassUtils;

/**
 * Implementation of CommandHandlerLocatorInterface
 */
class CommandHandlerLocator implements CommandHandlerLocatorInterface
{

    /**
     * @var CommandHandlerInterface[]
     */
    private $handlers = array();

    /**
     * @param CommandInterface $command
     *
     * @return CommandHandlerInterface
     * @throws InvalidArgumentException
     */
    public function getCommandHandler(CommandInterface $command)
    {
        $commandClassName = get_class($command);

        if (!$this->isKnownCommand($commandClassName)) {
            throw new InvalidArgumentException(
                sprintf(
                    'No handler registered for command "%s".',
                    $commandClassName
                )
            );
        }

        return $this->handlers[strtolower($commandClassName)];
    }

    /**
     * @return CommandHandlerInterface[]
     */
    public function getRegisteredCommandHandlers()
    {
        return $this->handlers;
    }

    /**
     * @param string                  $commandClassName
     * @param CommandHandlerInterface $commandHandler
     *
     * @throws InvalidArgumentException
     */
    public function register($commandClassName, CommandHandlerInterface $commandHandler)
    {
        $this->assertNamingConventionSatisfied($commandClassName, get_class($commandHandler));

        if ($this->isKnownCommand($commandClassName)) {
            throw new InvalidArgumentException(
                sprintf(
                    'A command handler has already been defined for the command "%s". Previous handler: %s. New handler: %s',
                    $commandClassName,
                    get_class($this->handlers[strtolower($commandClassName)]),
                    get_class($commandHandler)
                )
            );
        }
        $this->handlers[strtolower($commandClassName)] = $commandHandler;
    }

    /**
     * @param string $commandClassName
     * @param string $handlerClassName
     *
     * @throws InvalidArgumentException
     */
    private function assertNamingConventionSatisfied($commandClassName, $handlerClassName)
    {
        if (!$this->isNamingConventionSatisfied($commandClassName, $handlerClassName)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Command Handler does not follow naming convention. Expected: "%s". "%s" given.',
                    $this->getExpectedHandlerName($commandClassName),
                    ClassUtils::shortName($handlerClassName)
                )
            );
        }
    }

    /**
     * @param string $commandClassName
     * @param string $handlerClassName
     *
     * @return bool
     */
    private function isNamingConventionSatisfied($commandClassName, $handlerClassName)
    {
        $handlerClassName = ClassUtils::shortName($handlerClassName);

        return $this->getExpectedHandlerName($commandClassName) === $handlerClassName;
    }

    /**
     * @param string $commandClassName
     *
     * @return string
     */
    private function getExpectedHandlerName($commandClassName)
    {
        $commandClassName = ClassUtils::shortName($commandClassName);
        if ('Command' === substr($commandClassName, -7)) {
            $commandClassName = substr($commandClassName, 0, -7);
        }

        return $commandClassName . 'CommandHandler';
    }

    /**
     * @param string $commandClassName
     *
     * @return bool
     */
    private function isKnownCommand($commandClassName)
    {
        return isset($this->handlers[strtolower($commandClassName)]);
    }
}
