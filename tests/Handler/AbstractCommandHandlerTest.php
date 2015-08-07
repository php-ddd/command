<?php
namespace PhpDDD\Command\Handler;

use PhpDDD\Command\CommandInterface;
use PhpDDD\Command\Exception\InvalidArgumentException;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

/**
 * Test of the AbstractCommandHandler class
 */
class AbstractCommandHandlerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var AbstractCommandHandler|PHPUnit_Framework_MockObject_MockObject
     */
    private $instance;

    /**
     * @var CommandInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $command;

    public function setUp()
    {
        $this->command  = $this->getMockBuilder('PhpDDD\\Command\\CommandInterface')->getMock();
        $this->instance = $this->getMockForAbstractClass('PhpDDD\Command\Handler\AbstractCommandHandler');
    }

    public function testSupportsCommand()
    {
        $this->instance->expects($this->any())
            ->method('getSupportedCommandClassName')
            ->willReturn(get_class($this->command));

        $this->assertTrue($this->instance->supports(get_class($this->command)));
    }

    public function testSupportsCommandNotAllowed()
    {
        $this->instance->expects($this->any())
            ->method('getSupportedCommandClassName')
            ->willReturn('MyCommand');

        $this->assertFalse($this->instance->supports($this->command));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testHandleUnknownCommand()
    {
        $this->instance->expects($this->any())
            ->method('supports')
            ->willReturn(false);

        $this->instance->handle($this->command);
        $this->assertTrue(false);
    }

    public function testHandleCommand()
    {
        $this->instance->expects($this->any())
            ->method('getSupportedCommandClassName')
            ->willReturn(get_class($this->command));

        $this->instance->handle($this->command);
        $this->assertTrue(true);
    }
}
