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
				// If no derived table name has been specified, then fallback to using the main (FROM) table name
				$selects[] = '`' . $this->from . '`.`' . $select . '`';
			} else {
				$selects[] = '`' . str_replace('.', '`.`', $select) . '`';
			}
		}

		$sql[] = 'SELECT ' . ($this->format ? "\n\t" : '') . (!empty($selects) ? implode(($this->format ? "\n\t" : '') . ',', $selects) : '`' . $this->from . '`.*');
		$sql[] = 'FROM ' . ($this->format ? "\n\t" : '') . '`' . $this->from . '`';

		// Where clauses
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
					$wheres[] = $field . ($this->format ? ' ' : '') . $details['operator'] . ($this->format ? ' ' : '') . $details['value'];
				}
			}
			$sql[] = 'WHERE ' . ($this->format ? "\n\t" : '') . implode(' AND ' . ($this->format ? "\n\t" : '') . '', $wheres);
		}

		// Join clauses
		if (!empty($this->join)) {
			foreach ($this->join as $table => $details) {
				$sql[] = strtoupper($details['type']) . ' JOIN `' . $table . '` ON ' . $details['condition'];
			}
		}

		// Limit Clause
		if ($this->limit > 0) {
			$sql[] = 'LIMIT ' . (int) $this->limit;
		}

		if ($this->format) {
			return implode("\n", $sql) . ';';
		} else {
			return implode(' ', $sql) . ';';
		}
	}
}