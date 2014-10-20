<?php
namespace PhpDDD\Command\Bus;

use PhpDDD\Command\CommandInterface;
use PhpDDD\Command\Handler\CommandHandlerInterface;
use PhpDDD\Domain\AbstractAggregateRoot;
use PhpDDD\Event\Bus\EventBusInterface;

class CommandBusEventDispatcher implements CommandBusInterface
{

    /**
     * @var CommandBusInterface
     */
    private $commandBus;

    /**
     * @var EventBusInterface
     */
    private $eventBus;

    /**
     * @param CommandBusInterface $commandBus
     * @param EventBusInterface   $eventBus
     */
    public function __construct(CommandBusInterface $commandBus, EventBusInterface $eventBus)
    {
        $this->commandBus = $commandBus;
        $this->eventBus   = $eventBus;
    }

    /**
     * @param CommandInterface $command
     *
     * @return AbstractAggregateRoot[]
     */
    public function dispatch(CommandInterface $command)
    {
        $aggregateRoots = $this->commandBus->dispatch($command);

        foreach ($aggregateRoots as $aggregateRoot) {
            $this->dispatchEvents($aggregateRoot);
        }

        return $aggregateRoots;
    }

    /**
     * @return CommandHandlerInterface[]
     */
    public function getRegisteredCommandHandlers()
    {
        return $this->commandBus->getRegisteredCommandHandlers();
    }

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    private function dispatchEvents(AbstractAggregateRoot $aggregateRoot)
    {
        $events = $aggregateRoot->pullEvents();

        foreach ($events as $event) {
            $commandsToDispatch = $this->eventBus->publish($event);

            if (count($commandsToDispatch) > 0) {
                foreach ($commandsToDispatch as $command) {
                    $this->dispatch($command);
                }
            }
        }
    }
}
