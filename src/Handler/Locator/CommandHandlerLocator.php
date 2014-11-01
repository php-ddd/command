<?php
namespace PhpDDD\Command\Handler\Locator;

use PhpDDD\Command\CommandInterface;
use PhpDDD\Command\Handler\CommandHandlerInterface;
use PhpDDD\Command\Handler\Locator\Exception\BadCommandHandlerNamingException;
use PhpDDD\Command\Handler\Locator\Exception\CommandAlreadyRegisteredException;
use PhpDDD\Command\Handler\Locator\Exception\CommandNotRegisteredException;

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
     * @throws CommandNotRegisteredException
     */
    public function getCommandHandler(CommandInterface $command)
    {
        $commandType = get_class($command);

        if (!isset($this->handlers[strtolower($commandType)])) {
            throw new CommandNotRegisteredException(
                sprintf(
                    'No handler registered for command "%s".',
                    $commandType
                )
            );
        }

        return $this->handlers[strtolower($commandType)];
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
     * @throws CommandAlreadyRegisteredException
     */
    public function register($commandClassName, CommandHandlerInterface $commandHandler)
    {
        $this->assertCommandHandlerNamingConvention($commandClassName, get_class($commandHandler));

        if ($this->handlerAlreadyExistsForCommand($commandClassName)) {
            throw new CommandAlreadyRegisteredException(
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
     * @throws BadCommandHandlerNamingException
     */
    private function assertCommandHandlerNamingConvention($commandClassName, $handlerClassName)
    {
        if (!$this->isCommandHandlerFollowNamingConvention($commandClassName, $handlerClassName)) {
            throw new BadCommandHandlerNamingException(
                sprintf(
                    'Command Handler does not follow naming convention. Expected: "%s". "%s" given.',
                    $this->getExpectedHandlerName($commandClassName),
                    $this->getClassName($handlerClassName)
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
    private function isCommandHandlerFollowNamingConvention($commandClassName, $handlerClassName)
    {
        $handlerClassName = $this->getClassName($handlerClassName);

        return $this->getExpectedHandlerName($commandClassName) === $handlerClassName;
    }

    /**
     * @param string $classNameWithNamespace
     *
     * @return mixed
     */
    private function getClassName($classNameWithNamespace)
    {
        $classNameWithNamespace = explode('\\', $classNameWithNamespace);

        return end($classNameWithNamespace);
    }

    /**
     * @param string $commandClassName
     *
     * @return string
     */
    private function getExpectedHandlerName($commandClassName)
    {
        $commandClassName = $this->getClassName($commandClassName);
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
    private function handlerAlreadyExistsForCommand($commandClassName)
    {
        return isset($this->handlers[strtolower($commandClassName)]);
    }
}
