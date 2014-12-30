<?php
namespace PhpDDD\Command\Utils;

use PhpDDD\Command\Exception\InvalidArgumentException;
use PhpDDD\Command\Handler\Locator\CommandHandlerLocator;
use PHPUnit_Framework_TestCase;

/**
 * Test of the ClassUtils class
 */
class ClassUtilsTest extends PHPUnit_Framework_TestCase
{
    public function testShortName()
    {
        $object = new CommandHandlerLocator();
        $this->assertEquals(
            'CommandHandlerLocator',
            ClassUtils::shortName($object),
            'passing an object should return the class name without namespace'
        );
        $this->assertEquals(
            'CommandHandlerLocator',
            ClassUtils::shortName('PhpDDD\\Command\\Handler\\Locator\\CommandHandlerLocator'),
            'passing the fully qualified namespace should return the class name without namespace'
        );
        $this->assertEquals(
            'CommandHandlerLocator',
            ClassUtils::shortName(get_class($object)),
            'using get_class should return the class name without namespace'
        );
        $assertException = false;
        try {
            ClassUtils::shortName('This\\Class\\Does\\Not\\Exists');
        } catch (InvalidArgumentException $exception) {
            $assertException = true;
        }

        $this->assertTrue($assertException, 'A class that does not exists should raise an exception');
    }
}
