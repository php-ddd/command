<?php
namespace PhpDDD\Command\Handler;

use PhpDDD\Command\CommandInterface;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

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

    public function testAcceptCommand()
    {
        $this->instance->expects($this->any())
            ->method('getAllowedCommandClassName')
            ->willReturn(get_class($this->command));

        $this->assertTrue($this->instance->acceptCommand($this->command));
    }

    public function testAcceptCommandNotAllowed()
    {
        $this->instance->expects($this->any())
            ->method('getAllowedCommandClassName')
            ->willReturn('MyCommand');

        $this->assertFalse($this->instance->acceptCommand($this->command));
    }

    protected function setUp()
    {
        $this->command  = $this->getMockBuilder('PhpDDD\Command\CommandInterface')->getMock();
        $this->instance = $this->getMockForAbstractClass('PhpDDD\Command\Handler\AbstractCommandHandler');
    }
}
