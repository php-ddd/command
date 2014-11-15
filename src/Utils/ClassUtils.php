<?php
namespace PhpDDD\Command\Utils;

use PhpDDD\Command\Exception\InvalidArgumentException;

/**
 * Tools to transform class to name
 */
final class ClassUtils
{
    /**
     * @param object|string $objectOrNamespace
     *
     * @return string
     */
    public static function shortName($objectOrNamespace)
    {
        if (is_object($objectOrNamespace)) {
            $objectOrNamespace = get_class($objectOrNamespace);
        } elseif (!class_exists($objectOrNamespace)) {
            throw new InvalidArgumentException(
                sprintf(
                    'There is no class named %s',
                    $objectOrNamespace
                )
            );
        }

        $classNameWithNamespace = explode('\\', $objectOrNamespace);

        return end($classNameWithNamespace);
    }
}
