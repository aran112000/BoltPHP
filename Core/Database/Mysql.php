<?php
namespace Core\Database;

/**
 * Class Mysql - MySQL database layer, this should be used for all MySQL queries
 *
 * @package Core\Database
 */
class Mysql extends Database {

	/**
	 * @param string $server
	 * @param string $username
	 * @param string $password
	 * @param string $database
	 * @return resource
	 */
	protected function doConnect($server, $username, $password, $database) {
		// TODO: Implement doConnect() method.
	}
}