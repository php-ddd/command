<?php
namespace PhpDDD\Command\Test\Utils;

use PhpDDD\Command\Exception\InvalidArgumentException;
use PhpDDD\Command\Utils\ClassUtils;
use PHPUnit_Framework_TestCase;

/**
 * Test of the ClassUtils class
 */
class ClassUtilsTest extends PHPUnit_Framework_TestCase
{
    public function testShortName()
    {
        $object = new ClassUtilsTest();
        $this->assertEquals(
            'ClassUtilsTest',
            ClassUtils::shortName($object),
            'passing an object should return the class name without namespace'
        );
        $this->assertEquals(
            'ClassUtilsTest',
            ClassUtils::shortName('PhpDDD\\Command\\Test\\Utils\\ClassUtilsTest'),
            'passing the fully qualified namespace should return the class name without namespace'
        );
        $this->assertEquals(
            'ClassUtilsTest',
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
