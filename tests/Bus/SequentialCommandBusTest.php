<?php
namespace PhpDDD\Command\Test\Bus;

use PhpDDD\Command\Bus\SequentialCommandBus;
use PHPUnit_Framework_TestCase;

/**
 * Test of the SequentialCommandBus class
 */
class SequentialCommandBusTest extends PHPUnit_Framework_TestCase
{
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
}
