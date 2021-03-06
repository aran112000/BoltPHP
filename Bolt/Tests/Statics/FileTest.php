<?php
namespace Bolt\Tests\Statics;

use Bolt\Statics\File;

/**
 * Class FileTest
 *
 * @package Bolt\Tests\Statics
 */
class FileTest extends \PHPUnit_Framework_TestCase {

    /**
     *
     */
    public function testGetExtension() {
        $this->assertEquals('pdf', File::getExtension('/file/path/file.pdf'));
        $this->assertEquals('pdf', File::getExtension('/file/path/file.PDF'));
        $this->assertEquals('jpg', File::getExtension('/file/path/file.test.jpg'));
        $this->assertEquals('psd', File::getExtension('/file/path/file.test.jpg.psd'));
    }
}