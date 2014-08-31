<?php
namespace Core\Database;

/**
 * Class Mysql - MySQL database layer, this should be used for all MySQL queries
 *
 * @package Core\Database
 */
class Mysql extends Database {

	private $connection = null;

	/**
	 * @param string $server
	 * @param string $username
	 * @param string $password
	 * @param string $database
	 */
	protected function doConnect($server, $username, $password, $database) {
		if ($this->connection === null) {
			$this->connection = mysqli_connect($server, $username, $password, $database);
		}
	}

	/**
	 * @return bool|\mysqli_result
	 */
	protected function exec() {
		return mysqli_query($this->connection, $this->getSql());
	}

	/**
	 * @return string
	 */
	public function getSql() {
		$sql = [];
		$selects = [];
		foreach ($this->select as $select) {
			if (!strstr($select, '.')) {
				$selects[] = '`' . $this->from . '`.`' . $select . '`';
			} else {
				$selects[] = '`' . str_replace('.', '`.`', $select) . '`';
			}
		}
		$sql[] = 'SELECT ' . implode(',', $selects);
		$sql[] = 'FROM `' . $this->from . '`';
		if (!empty($this->where)) {
			$wheres = [];
			foreach ($this->where as $field => $details) {
				if (!strstr($field, '.')) {
					$field = '`' . $this->from . '`.`' . $field . '`';
				} else {
					$field = '`' . str_replace('.', '`.`', $field) . '`';
				}
				if ($details['raw']) {
					$wheres[] = $field . $details['value'];
				} else {
					$wheres[] = $field . $details['operator'] . $details['value'];
				}
			}
			$sql[] = 'WHERE ' . implode(' AND ', $wheres);
		}
		if (!empty($this->join)) {
			foreach ($this->join as $table => $details) {
				$sql[] = strtoupper($details['type']) . ' JOIN `' . $table . '` ON ' . $details['condition'];
			}
		}
		if ($this->limit > 0) {
			$sql[] = 'LIMIT ' . (int) $this->limit;
		}

		return implode("\n", $sql) . ';';
	}
}