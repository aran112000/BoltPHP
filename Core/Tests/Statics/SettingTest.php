<?php

namespace Core\Tests\Statics;

use Core\Statics\Setting;

/**
 * Class SettingTest
 * @package Core\Tests\Statics
 */
class SettingTest extends \PHPUnit_Framework_TestCase {

    /**
     * @throws \Core\Exception\Fatal
     * @throws \Core\Exception\Warning
     */
    public function testGet() {
        // Test a missing setting
        $this->assertEquals(null, Setting::get('non_existent_setting_name'));
        // Test a valid setting
        $this->assertEquals('CloudFit', Setting::get('site_name'));
    }

}