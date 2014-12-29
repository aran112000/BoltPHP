<?php
namespace Bolt;

/**
 * Class Init - Used to initialise Bolt and handle a request
 *
 * @package Bolt
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

		if (!defined('BACKGROUND_PROCESS') || !BACKGROUND_PROCESS) {
			// Background processes purely load the framework but don't generate any HTML output
			$this->initPage();
		}
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
			$this->page = new Components\Layout\Page();
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