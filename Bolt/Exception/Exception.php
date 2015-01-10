<?php
namespace Bolt\Exception;

/**
 * Class Exception
 * @package Bolt\Exception
 */
class Exception extends \Exception {

    /**
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($message = '', $code = 0, \Exception $previous = null) {
		parent::__construct($message, $code, $previous);

		$this->doLogError($message);
	}

    /**
     * TODO - Need to implement a proper logger to work based on the environment being ran within
     *
     * @param $message
     */
    protected function doLogError($message) {
		trigger_error('Platform error: ' . $message);
	}
}
