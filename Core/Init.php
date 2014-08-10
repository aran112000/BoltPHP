<?php
namespace Core;

/**
 * Class Init - Used to initialise Core and handle a request
 *
 * @package Core
 */
class Init {

	/**
	 *
	 */
	public function __construct() {

	}

	protected function doSanitizeRequestVars() {
		if (isset($_REQUEST) && !empty($_REQUEST)) {
			foreach ($_REQUEST as $key => &$value) {
				if (isset($_GET) && isset($_GET[$key])) {

				} else if (isset($_POST) && isset($_POST[$key])) {

				}
			}
		}
	}
}