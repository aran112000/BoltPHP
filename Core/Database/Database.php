<?php
namespace Core\Database;

/**
 * Class Database
 *
 * @package Core\Database
 */
abstract class Database {

	protected $select = [];
	protected $from = '';
	protected $join = [];
	protected $where = [];
	protected $group_by = [];
	protected $order_by = [];
	protected $having = [];
	protected $limit = -1;

	protected $parameters = [];

	CONST PARAMETER_SEPARATOR = ':';

	private $operators = [
		'>=',
		'<=',
		'<>',
		'!=',
		'=',
		'NOT IN',
		'IN',
	];

	/**
	 * @param string $server
	 * @param string $username
	 * @param string $password
	 * @param string $database
	 */
	abstract protected function doConnect($server, $username, $password, $database);

	/**
	 * @return bool|mixed
	 */
	abstract protected function exec();

	/**
	 * @return string
	 */
	abstract public function getSql();

	/**
	 * @param $selects
	 * @return $this
	 */
	public function select($selects) {
		if (!is_array($selects)) {
			$selects = explode(',', $selects);
		}

		foreach ($selects as &$select) {
			$this->doNormaliseTableAndFieldNames($select);
		}

		$this->select = $selects;

		return $this;
	}

	/**
	 * @param $from
	 * @return $this
	 */
	public function from($from) {
		$this->doNormaliseTableAndFieldNames($from);
		$this->from = $from;

		return $this;
	}

	/**
	 * @param string $table
	 * @param array  $conditions
	 * @param string $type
	 * @return $this
	 */
	public function join($table, array $conditions, $type = 'left') {
		$this->doNormaliseTableAndFieldNames($table);
		$this->doNormaliseTableAndFieldNames($conditions);
		$this->join[$table] = [
			'type' => $type,
			'condition' => $conditions,
		];

		return $this;
	}

	/**
	 * @param string $table
	 * @param array  $conditions
	 * @return Database
	 */
	public function leftJoin($table, array $conditions) {
		return $this->join($table, $conditions);
	}

	/**
	 * @param string $table
	 * @param array  $conditions
	 * @return Database
	 */
	public function rightJoin($table, array $conditions) {
		return $this->join($table, $conditions);
	}

	/**
	 * @param array|string $conditions
	 * @return $this
	 */
	public function where($conditions) {
		if (!is_array($conditions)) {
			$conditions = explode(' and ', $conditions);
		}

		$this->doNormaliseTableAndFieldNames($conditions);

		foreach ($conditions as $condition) {
			foreach ($this->operators as $operator) {
				if (strstr($condition, $operator)) {
					$parts = explode($operator, $condition, 2);

					$field = trim($parts[0]);
					$value = trim($parts[1]);

					// Check if this value is a raw SQL value and if so, it must be auto parameterized to prevent possible SQL injection
					if (substr($value, 0, 1) != self::PARAMETER_SEPARATOR) {
						// Strip string wrapping quotes
						$value = trim($value, '\'');
						$parameter_key = md5($this->getEncodedParameterKey($field));
						// Prevent any auto generated keys overlapping
						while (isset($this->parameters[$parameter_key])) {
							$parameter_key++;
							$parameter_key = md5($parameter_key);
						}
						$this->parameters[$parameter_key] = $value;

						$value = self::PARAMETER_SEPARATOR . $parameter_key;
					}

					$this->where[$field] = [
						'value' => $value,
						'operator' => $operator,
						'raw' => false,
					];
					break;
				}
			}
		}

		return $this;
	}

	/**
	 * Used to normalise our fields - Removes characters such as the ` commonly used to wrap MySQL field/table names
	 *
	 * @param string|array &$fieldName
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

	/**
	 * @param $key
	 * @return mixed
	 */
	private function getEncodedParameterKey($key) {
		return preg_replace('/[^a-zA-Z0-9_]/', '', $key);
	}
}