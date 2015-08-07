<?php
namespace PhpDDD\Command\Bus;

use Exception;
use PhpDDD\Command\CommandInterface;
use PhpDDD\Command\Handler\Locator\CommandHandlerLocatorInterface;
use PhpDDD\Domain\AbstractAggregateRoot;

/**
 * The sequential command bus works like a stack.
 * It wait until a command is fully processed to invoke the next one.
 * Each command will be happened to the tail of the stack before being processed.
 */
class SequentialCommandBus implements CommandBusInterface
{

    /**
     * @var CommandHandlerLocatorInterface
     */
    private $locator;

    /**
     * @var CommandInterface[]
     */
    private $commandStack = array();

    /**
     * @var bool
     */
    private $executing = false;

    /**
     * @param CommandHandlerLocatorInterface $locator
     */
    public function __construct(CommandHandlerLocatorInterface $locator)
    {
        $this->locator = $locator;
    }

    /**
     * Sequentially execute commands
     *
     * If an exception occurs in any command it will be put on a stack
     * of exceptions that is thrown only when all the commands are processed.
     *
     * [@inheritdoc}
     */
    public function dispatch(CommandInterface $command)
    {
        $this->commandStack[] = $command;

        if ($this->executing) {
            return array();
        }

        $first          = true;
        $aggregateRoots = array();

        while ($command = array_shift($this->commandStack)) {
            $aggregateRoots[] = $this->invokeHandler($command, $first);
            $first            = false;
        }

        return $aggregateRoots;
    }

    /**
     * {@inheritdoc}
     */
    public function getRegisteredCommandHandlers()
    {
        return $this->locator->getRegisteredCommandHandlers();
    }

    /**
     * @param CommandInterface $command
     * @param bool             $first
     *
     * @throws Exception
     * @return AbstractAggregateRoot|null
     */
    protected function invokeHandler(CommandInterface $command, $first)
    {
        $aggregateRoot = null;
        try {
            $this->executing = true;

            $commandHandler = $this->locator->getCommandHandlerForCommand($command);

            $aggregateRoot = $commandHandler->handle($command);
        } catch (Exception $exception) {
            $this->executing = false;
            $this->handleException($exception, $first);
        }

        $this->executing = false;

        return $aggregateRoot;
    }

    /**
     * Only throw the exception if this is the first dispatch of the sequential dispatching.
     * If we have a sub-command that throw an exception, it should not prevent other sub-command to be executed.
     * We may need to rollback the whole process.
     *
     * @param Exception $exception
     * @param bool      $first
     *
     * @throws \Exception
     */
    protected function handleException(Exception $exception, $first)
    {
        if ($first) {
            throw $exception;
        }
    }
}
