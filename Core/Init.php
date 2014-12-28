<?php
namespace Core;
use Core\Exception\Core;

/**
 * Class Init - Used to initialise Core and handle a request
 *
 * @package Core
 */
class Init {

	protected $page = null;

	// Define the current code environment - This is used to setup our error reporting levels
	protected $production = false;

	/**
	 *
	 */
	public function __construct() {
		$this->setErrorReportingLevel();
		$this->initConstants();
		$this->initPage();
	}

	protected function setErrorReportingLevel() {
		if ($this->production) {
			error_reporting(0);
		} else {
			error_reporting(-1);
		}
	}

	/**
	 * Used to initialise a batch of helper constants
     */
	protected function initConstants() {
		date_default_timezone_set('Europe/London');

		define('DS', DIRECTORY_SEPARATOR);
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
		define('HOST', (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '127.0.0.1'));
		define('URI', (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/'));
		define('IP', (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '000.000.000.000'));
	}

	protected function initPage() {
		if ($this->page === null) {
			$this->page = new \Core\Components\Layout\Page();
		}
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