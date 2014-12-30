<?php
namespace PhpDDD\Command\Bus;

use PhpDDD\Command\CommandInterface;
use PhpDDD\Command\Handler\CommandHandlerInterface;
use PHPUnit_Framework_TestCase;

/**
 * Test of the SequentialCommandBus class
 */
class SequentialCommandBusTest extends PHPUnit_Framework_TestCase
{

    private $bus;

    public function setUp()
    {
        $locator = $this
            ->getMockBuilder('\PhpDDD\Command\Handler\Locator\CommandHandlerLocatorInterface')
            ->setMethods(array('getCommandHandlerForCommand'))
            ->getMockForAbstractClass();

        $locator->expects($this->any())
            ->method('getCommandHandlerForCommand')
            ->willReturn($this->getCommandHandlerMock('MyCommandHandler'));

        $this->bus = new SequentialCommandBus($locator);
    }

    public function testGetRegisteredCommandHandlers()
    {
        $locator = $this
            ->getMockBuilder('\PhpDDD\Command\Handler\Locator\CommandHandlerLocatorInterface')
            ->getMockForAbstractClass();

        $locator->expects($this->any())
            ->method('getRegisteredCommandHandlers')
            ->willReturn(array());
        $object = new SequentialCommandBus($locator);

        $this->assertEquals(array(), $object->getRegisteredCommandHandlers());
    }

    public function testDispatch()
    {
        $result = $this->bus->dispatch($this->getCommandMock());

        $this->assertCount(1, $result);
    }

    /**
     * @expectedException \Exception
     */
    public function testDispatchWithExceptionInCommandHandler()
    {
        $handler = $this
            ->getMockBuilder('\PhpDDD\Command\Handler\CommandHandlerInterface')
            ->setMockClassName('MyCommandHandler')
            ->setMethods(array('handle'))
            ->getMockForAbstractClass();

        $handler->expects($this->any())
            ->method('handle')
            ->willThrowException(new \Exception());

        $locator = $this
            ->getMockBuilder('\PhpDDD\Command\Handler\Locator\CommandHandlerLocatorInterface')
            ->setMethods(array('getCommandHandlerForCommand'))
            ->getMockForAbstractClass();

        $locator->expects($this->any())
            ->method('getCommandHandlerForCommand')
            ->willReturn($handler);

        $bus = new SequentialCommandBus($locator);
        $bus->dispatch($this->getCommandMock());
    }

    /**
     */
    public function testDispatchWithExceptionInSecondCommandHandler()
    {
        $handler = $this
            ->getMockBuilder('\PhpDDD\Command\Handler\CommandHandlerInterface')
            ->setMockClassName('MyCommandHandler')
            ->setMethods(array('handle'))
            ->getMockForAbstractClass();

        $handler->expects($this->at(0))
            ->method('handle')
            ->will($this->returnCallback(array($this, 'myCallback')));

        $handler->expects($this->at(1))
            ->method('handle')
            ->willThrowException(new \Exception());

        $locator = $this
            ->getMockBuilder('\PhpDDD\Command\Handler\Locator\CommandHandlerLocatorInterface')
            ->setMethods(array('getCommandHandlerForCommand'))
            ->getMockForAbstractClass();

        $locator->expects($this->any())
            ->method('getCommandHandlerForCommand')
            ->willReturn($handler);

        $this->bus = new SequentialCommandBus($locator);
        $this->bus->dispatch($this->getCommandMock());
    }

    public function myCallback()
    {
        $this->bus->dispatch($this->getCommandMock());

        return true;
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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CommandInterface
     */
    private function getCommandMock()
    {
        return $this
            ->getMockBuilder('PhpDDD\\Command\\CommandInterface')
            ->setMockClassName('MyCommand')
            ->getMock();
    }
}
