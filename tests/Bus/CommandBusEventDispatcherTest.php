<?php
namespace PhpDDD\Command\Bus;

use PhpDDD\Command\Handler\CommandHandlerInterface;
use PHPUnit_Framework_TestCase;

class CommandBusEventDispatcherTest extends PHPUnit_Framework_TestCase
{
    public function testDispatch()
    {
        $bus = new CommandBusEventDispatcher($this->getCommandBusMock(1), $this->getEmptyEventBusMock());

        $bus->dispatch($this->getCommandMock());
        $this->assertCount(1, $bus->getRegisteredCommandHandlers());
    }

    public function testDispatchMultipleCommand()
    {
        $bus = new CommandBusEventDispatcher($this->getCommandBusMock(2), $this->getEventBusWithOneEventMock());

        $bus->dispatch($this->getCommandMock());
        $this->assertCount(1, $bus->getRegisteredCommandHandlers());
    }

    private function getCommandMock()
    {
        $mock = $this->getMockBuilder('PhpDDD\Command\CommandInterface')
            ->getMockForAbstractClass();

        return $mock;
    }

    private function getCommandBusMock($nbDispatchCall)
    {
        $mock = $this->getMockBuilder('PhpDDD\Command\Bus\CommandBusInterface')
            ->setMethods(array('dispatch', 'getRegisteredCommandHandlers'))
            ->getMockForAbstractClass();

        $mock->expects($this->at(0))
            ->method('dispatch')
            ->willReturn(array(array($this->getAggregateMock()), $this->getAggregateMock()));

        if ($nbDispatchCall > 1) {
            $mock->expects($this->at(1))
                ->method('dispatch')
                ->willReturn(array());
        }

        $mock->expects($this->once())
            ->method('getRegisteredCommandHandlers')
            ->willReturn(
                array(get_class($this->getAggregateMock()) => $this->getCommandHandlerMock('MyCommandHandler'))
            );

        return $mock;
    }

    private function getAggregateMock()
    {
        $mock = $this->getMockBuilder('PhpDDD\Domain\AbstractAggregateRoot')
            ->setMethods(array('pullEvents'))
            ->getMockForAbstractClass();

        $mock->expects($this->any())
            ->method('pullEvents')
            ->willReturn(array($this->getEventMock()));

        return $mock;
    }

    private function getEmptyEventBusMock()
    {
        $mock = $this->getMockBuilder('PhpDDD\Domain\Event\Bus\EventBusInterface')
            ->setMethods(array('publish'))
            ->getMockForAbstractClass();

        $mock->expects($this->any())
            ->method('publish')
            ->willReturn(array());

        return $mock;
    }

    private function getEventBusWithOneEventMock()
    {
        $mock = $this->getMockBuilder('PhpDDD\Domain\Event\Bus\EventBusInterface')
            ->setMethods(array('publish'))
            ->getMockForAbstractClass();

        $mock->expects($this->any())
            ->method('publish')
            ->willReturn(array($this->getCommandMock()));

        return $mock;
    }

    private function getEventMock()
    {
        $mock = $this->getMockForAbstractClass('PhpDDD\Domain\Event\EventInterface');

        return $mock;
    }

    /**
     * @param $commandHandlerClassName
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|CommandHandlerInterface
     */
    private function getCommandHandlerMock($commandHandlerClassName)
    {
        return $this
            ->getMockBuilder('\PhpDDD\Command\Handler\CommandHandlerInterface')
            ->setMockClassName($commandHandlerClassName)
            ->getMockForAbstractClass();
    }
}
