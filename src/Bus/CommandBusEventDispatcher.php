<?php
namespace PhpDDD\Command\Bus;

use PhpDDD\Command\CommandInterface;
use PhpDDD\Command\Handler\CommandHandlerInterface;
use PhpDDD\Domain\AbstractAggregateRoot;
use PhpDDD\Domain\Event\Bus\EventBusInterface;
use PhpDDD\Domain\Event\EventInterface;
use PhpDDD\Domain\Event\Listener\EventListenerInterface;

/**
 * Class that act as a CommandBus and dispatch events
 *
 * @see php-ddd/event project
 */
class CommandBusEventDispatcher implements CommandBusInterface, EventBusInterface
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

        $this->dispatchEvents($aggregateRoots);

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
     * @param array $elements
     */
    private function dispatchEvents(array $elements)
    {
        foreach ($elements as $element) {
            if (is_array($element)) {
                $this->dispatchEvents($element);

                return;
            }
            if ($element instanceof AbstractAggregateRoot) {
                $this->dispatchEventsForAggregateRoot($element);
            }
        }
    }

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    private function dispatchEventsForAggregateRoot(AbstractAggregateRoot $aggregateRoot)
    {
        $events = $aggregateRoot->pullEvents();

        foreach ($events as $event) {
            $this->publish($event);
        }
    }

    /**
     * Publishes the event $event to every EventListener that wants to.
     *
     * @param EventInterface $event
     *
     * @return array data returned by each EventListener
     */
    public function publish(EventInterface $event)
    {
        $commandsToDispatch = $this->eventBus->publish($event);

        foreach ($commandsToDispatch as $command) {
            if ($command instanceof CommandInterface) {
                $this->dispatch($command);
            }
        }
    }

    /**
     * Get the list of every EventListener defined in the EventBus.
     * This might be useful for debug
     *
     * @return EventListenerInterface[]
     */
    public function getRegisteredEventListeners()
    {
        return $this->eventBus->getRegisteredEventListeners();
    }
}
