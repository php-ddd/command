<?php
namespace PhpDDD\Command\Handler\Locator;

use PhpDDD\Command\CommandInterface;
use PhpDDD\Command\Handler\CommandHandlerInterface;
use PHPUnit_Framework_TestCase;

class CommandHandlerLocatorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var CommandHandlerLocator
     */
    private $locator;

    public function testRegister()
    {
        $this->locator->register('MyCommand', $this->getCommandHandlerMock('MyCommandHandler'));
        $this->assertTrue(true, 'command should be registered');
    }

    /**
     * @expectedException \PhpDDD\Command\Handler\Locator\Exception\BadCommandHandlerNamingException
     */
    public function testRegisterWithBadNamingConvention()
    {
        $mock = $this->getCommandHandlerMock('MyCommand');
        $this->locator->register('WrongCommandHandler', $mock);
    }

    /**
     * @expectedException \PhpDDD\Command\Handler\Locator\Exception\CommandAlreadyRegisteredException
     */
    public function testRegisterTwoHandlerForSameCommand()
    {
        $this->locator->register('MyCommand', $this->getCommandHandlerMock('MyCommandHandler'));
        $this->locator->register('MyCommand', $this->getCommandHandlerMock('MyCommandHandler'));
    }

    public function testGetCommandHandler()
    {
        $commandMock = $this->getCommandMock();
        $commandClassName = get_class($commandMock);
        $commandHandlerClassName = $commandClassName . 'CommandHandler';
        $this->locator->register($commandClassName, $this->getCommandHandlerMock($commandHandlerClassName));
        $handler     = $this->locator->getCommandHandler($commandMock);

        $this->assertNotNull($handler);
    }

    /**
     * @expectedException \PhpDDD\Command\Handler\Locator\Exception\CommandNotRegisteredException
     */
    public function testGetCommandHandlerWithUnknownCommand()
    {
        $commandMock = $this->getCommandMock();
        $this->locator->getCommandHandler($commandMock);
    }

    protected function setUp()
    {
        $this->locator = new CommandHandlerLocator();
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
            ->getMockBuilder('\PhpDDD\Command\CommandInterface')
            ->getMock();
    }
}
