<?php
namespace Bolt\Tests\Components;

use Bolt\Components\Router,
    Bolt\Tests\PHPUnit;

/**
 * Class RouterTest
 * @package Bolt\Tests\Components
 */
class RouterTest extends PHPUnit {

    /**
     *
     */
    public function testGetUrlMapping() {
        $router = new Router();
        $response = $this->doCallNonPublicMethod($router, 'getUrlMapping');

        $this->assertEquals('Pages\Home', $response);
    }
}