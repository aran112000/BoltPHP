<?php
namespace Bolt\Tests;

/**
 * Class PHPUnit
 * @package Bolt\Tests
 */
class PHPUnit extends \PHPUnit_Framework_TestCase {

    /**
     * Allows calling of both Protected & Private methods to allow more in-depth testing
     *
     * @param \stdClass|string $class
     * @param string           $method_name
     * @param array            $args
     *
     * @return mixed
     */
    protected function doCallNonPublicMethod($class, $method_name, array $args = []) {
        $reflection_class = new \ReflectionClass($class);
        $method = $reflection_class->getMethod($method_name);
        $method->setAccessible(true);

        if (is_string($class)) {
            $class = new $class();
        }

        return $method->invokeArgs($class, $args);
    }
}