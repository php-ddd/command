<?php
namespace PhpDDD\Command\Handler\Locator;

use PhpDDD\Command\CommandInterface;
use PhpDDD\Command\Handler\CommandHandlerInterface;
use PHPUnit_Framework_TestCase;

/**
 * Test of the CommandHandlerLocator
 */
class CommandHandlerLocatorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var CommandHandlerLocator
     */
    private $locator;

    public function setUp()
    {
        $this->locator = new CommandHandlerLocator();
    }

    public function testRegister()
    {
        $command = $this->getCommandMock();
        $this->locator->register(get_class($command), $this->getCommandHandlerMock('MyCommandHandler'));
        $this->assertTrue(true, 'command should be registered');
    }

    /**
     * @expectedException \PhpDDD\Command\Exception\InvalidArgumentException
     */
    public function testRegisterCommandNotImplementingCommandInterface()
    {
        $commandMock             = $this->getMock('PhpDDD\Command\Handler\Locator\CommandHandlerLocator');
        $commandClassName        = get_class($commandMock);
        $commandHandlerClassName = $commandClassName.'CommandHandler';
        $this->locator->register($commandClassName, $this->getCommandHandlerMock($commandHandlerClassName));
        $handler     = $this->locator->getCommandHandlerForCommand($commandMock);

        $this->assertNotNull($handler);
    }

    /**
     * @expectedException \PhpDDD\Command\Exception\InvalidArgumentException
     */
    public function testRegisterWithBadNamingConvention()
    {
        $mock = $this->getCommandHandlerMock('MyCommandHandler');
        // need to be a valid class
        $this->locator->register('PhpDDD\\Command\\Handler\\Locator\\CommandHandlerLocator', $mock);
        $this->assertFalse(true);
    }

    /**
     * @expectedException \PhpDDD\Command\Exception\InvalidArgumentException
     */
    public function testRegisterTwoHandlerForSameCommand()
    {
        $this->locator->register('MyCommand', $this->getCommandHandlerMock('MyCommandHandler'));
        $this->locator->register('MyCommand', $this->getCommandHandlerMock('MyCommandHandler'));
    }

    public function testGetCommandHandler()
    {
        $commandMock             = $this->getCommandMock();
        $commandClassName        = get_class($commandMock);
        $commandHandlerClassName = $commandClassName.'Handler';
        $this->locator->register($commandClassName, $this->getCommandHandlerMock($commandHandlerClassName));
        $handler     = $this->locator->getCommandHandlerForCommand($commandMock);

        $this->assertNotNull($handler);
        $this->assertCount(1, $this->locator->getRegisteredCommandHandlers());
    }

    /**
     * @expectedException \PhpDDD\Command\Exception\NoCommandHandlerRegisteredException
     */
    public function testGetCommandHandlerWithUnknownCommand()
    {
        $commandMock = $this->getCommandMock();
        $this->locator->getCommandHandlerForCommand($commandMock);
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
