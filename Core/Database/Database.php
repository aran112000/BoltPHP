<?php
namespace Core\Database;

/**
 * Class Database
 *
 * @package Core\Database
 */
abstract class Database {

	/**
	 * @param string $server
	 * @param string $username
	 * @param string $password
	 * @param string $database
	 * @return resource
	 */
	abstract protected function doConnect($server, $username, $password, $database);

	protected function select($selects) {
		if (!is_array($selects)) {
			$selects = explode(',', $selects);
		}

		foreach ($selects as &$select) {
			$this->doNormaliseTableAndFieldNames($select);
		}
	}

	/**
	 * Used to normalise our fields - Removes characters such as the ` commonly used to wrap MySQL field/table names
	 *
	 * @param string|array $fieldName
	 */
	private function doNormaliseTableAndFieldNames(&$fieldName) {
		if (!is_array($fieldName)) {
			$fieldName = trim(str_replace('`', '', $fieldName));
		} else {
			foreach ($fieldName as &$value) {
				$value = trim(str_replace('`', '', $value));
			}
		}
	}
}