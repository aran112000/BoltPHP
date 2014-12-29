<?php
namespace Core\Exception;

class Exception extends \Exception {

	public function __construct($message = '', $code = 0, \Exception $previous = null) {
		parent::__construct($message, $code,  $previous);

		$this->do_log_error($message);
	}

	protected function do_log_error($message) {
		trigger_error('Platform error: ' . $message);
	}
}