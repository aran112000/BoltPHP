<?php

namespace Bolt\Tests\Statics;

use Bolt\Statics\Setting;

/**
 * Class SettingTest
 * @package Bolt\Tests\Statics
 */
class SettingTest extends \PHPUnit_Framework_TestCase {

    /**
     * @throws \Bolt\Exception\Fatal
     * @throws \Bolt\Exception\Warning
     */
    public function testGet() {
        // Test a missing setting
        $this->assertEquals(null, Setting::get('non_existent_setting_name'));
        // Test a valid setting (not from the database)
        $this->assertEquals('BoltPHP', Setting::get('site_name', null, false, false));
    }

}