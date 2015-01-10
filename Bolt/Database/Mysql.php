<?php

namespace Bolt\Database;

use Bolt\Exception\Fatal,
    Bolt\Statics\Setting,
    Bolt\Exception\Warning;

/**
 * Class Mysql - MySQL database layer, this should be used for all MySQL queries
 *
 * @package Bolt\Database
 */
class Mysql extends Database {

	/**
	 * @var null|\mysqli
     */
	private static $connection = null;

	/**
	 * @param string $server
	 * @param string $username
	 * @param string $password
	 * @param string $database
	 *
	 * @throws \Bolt\Exception\Fatal
	 */
	public function doConnect($server, $username, $password, $database) {
		if (static::$connection === null) {
            static::$connection = new \mysqli($server, $username, $password, $database);
			if (mysqli_connect_errno()) {
				throw new Fatal('Failed to connect to the MySQL server with error: ' . mysqli_connect_error());
			}
		}
	}

	/**
	 * @param null|string $class_name The name of the objects you wish to populate with a valid result set
	 *                                If left NULL (default value), the derived (FROM) database table will be used where
	 *                                a valid object exists with the same name e.g.
	 *
	 *                                /Objects/{FROM_TABLE_NAME}
	 *
	 * @return bool|\mysqli_result
	 * @throws \Bolt\Exception\Fatal
	 * @throws \Bolt\Exception\Warning
	 */
	public function exec($class_name = null) {
		if (static::$connection === null) {
			$this->doConnect(
				Setting::get('database_server', null, false, false),
				Setting::get('database_username'),
				Setting::get('database_password'),
				Setting::get('database_name')
			);
		}

		if (empty($this->parameters)) {
			if (!$result = static::$connection->query($this->getSql())) {
                $result = null;
                throw new Warning(static::$connection->error);
			}
		} else {
			// TODO This needs to use mysqli_prepare and the getSql() output will need to be updated from named parameters
			$result = null;
			throw new Fatal('Support for parameterized queries is coming soon');
		}

		return $this->getResultSet($result, $class_name);
	}

	/**
	 * @param \mysqli_result $result
	 * @param null|string    $class_name
	 *
	 * @return array|null
	 * @internal param bool $res
	 */
    private function getResultSet($result, $class_name) {
		$result_set = null;
		if ($result) {
			if ($class_name === null) {
				$class_name = ucfirst($this->from);
			}
			$class_name = '\Bolt\Objects\\' . $class_name;

			$result_set = [];
			while ($object = $result->fetch_object($class_name)) {
				$result_set[] = $object;
			}
		}

		return $result_set;
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

		// Group by clauses
		if (!empty($this->group_by)) {
			$group_by = [];
			foreach ($this->group_by as $group) {
				if (!strstr($group, '.')) {
					// If no derived table name has been specified, then fallback to using the main (FROM) table name
					$group_by[] = '`' . $this->from . '`.`' . $group . '`';
				} else {
					$group_by[] = '`' . str_replace('.', '`.`', $group) . '`';
				}
			}

			$sql[] = 'GROUP BY ' . ($this->format ? "\n\t" : '') . implode(($this->format ? "\n\t" : '') . ',', $group_by);
		}

		// Having clauses
		if (!empty($this->having)) {
			$having = [];
			foreach ($this->having as $hav) {
				if (!strstr($hav, '.')) {
					// If no derived table name has been specified, then fallback to using the main (FROM) table name
					$having[] = '`' . $this->from . '`.`' . $hav . '`';
				} else {
					$having[] = '`' . str_replace('.', '`.`', $hav) . '`';
				}
			}

			$sql[] = 'HAVING ' . ($this->format ? "\n\t" : '') . implode(($this->format ? "\n\t" : '') . ',', $having);
		}

		// Order by clauses
		if (!empty($this->order_by)) {
			$order_bys = [];
			foreach ($this->order_by as $order_by) {
				if (!strstr($order_by, '.')) {
					// If no derived table name has been specified, then fallback to using the main (FROM) table name
					$order_bys[] = '`' . $this->from . '`.`' . $order_by . '`';
				} else {
					$order_bys[] = '`' . str_replace('.', '`.`', $order_by) . '`';
				}
			}

			$sql[] = 'ORDER BY ' . ($this->format ? "\n\t" : '') . implode(($this->format ? "\n\t" : '') . ',', $order_bys);
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
