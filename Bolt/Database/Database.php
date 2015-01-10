<?php
namespace Bolt\Database;

/**
 * Class Database
 *
 * @package Bolt\Database
 */
abstract class Database {

	const PARAMETER_SEPARATOR = ':';

	protected $select = [];
	protected $from = '';
	protected $join = [];
	protected $where = [];
	protected $group_by = [];
	protected $having = [];
	protected $order_by = [];
	protected $limit = -1;

	public $format = false; // Makes resulting SQL slightly more legible

	protected $parameters = [];

	public $operators = [
		'>=',
		'<=',
		'<>',
		'!=',
		'>',
		'<',
		'=',
	];


	/**
	 * TODO - This is currently unused - RAW support exists but it's currently used
	 *
	 * When any of these values are found in a database value, the value will be used in it's RAW form.
	 * If we were to parameterize the values of a function it would break the SQL take this for example:
	 *
	 * This:
	 *   SELECT * FROM `table` WHERE `password` = MD5('YOUR VALUE') AND ...
	 * Would be converted into this:
	 *   SELECT * FROM `table` WHERE `password` = :parameter_key AND ...
	 *
	 * NOTE: Please ensure when using Database specific function calls, that you're escaping your inputs correctly
	 *       due to them being processed using the RAW value supplied
	 *
	 * Which would produce a completely different result - In all likelihood a NULL result set
	 *
	 * @var array
     */
	public $database_value_matches_for_raw = [
		'(',
		')',
		'IN',
		'NOT',
		'IS',
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
	 * @param array        $parameters
	 * @return $this
	 */
	public function where($conditions, array $parameters = []) {
		if (!is_array($conditions)) {
			$conditions = explode(' AND ', str_replace(' and ', ' AND ', $conditions));
		}

		$this->parameters = array_merge($this->parameters, $parameters);

		$this->doNormaliseTableAndFieldNames($conditions);

		foreach ($conditions as $key => $condition) {
			$where_opts = [
				'field' => '',
				'value' => '',
				'operator' => '=',
			];
			if (is_int($key)) {
				foreach ($this->operators as $operator) {
					if (strstr($condition, $operator)) {
						$parts = explode($operator, $condition, 2);

						$where_opts['field'] = trim($parts[0]);
						$where_opts['value'] = trim($parts[1]);
						$where_opts['operator'] = $operator;

						break;
					}
				}
			} else {
				$where_opts['field'] = trim($key);
				$where_opts['value'] = trim($condition);
			}

			$this->doStrictTypeValue($where_opts['value']);

			// Check if this value is a raw SQL value and if so, it must be auto parameterized to prevent possible SQL injection
			if (substr($where_opts['value'], 0, 1) !== self::PARAMETER_SEPARATOR) {
				// Strip string wrapping quotes
				$value = trim($where_opts['value'], '\'');
				$parameter_key = md5($where_opts['field']);
				// Prevent any auto generated keys overlapping
				while (isset($this->parameters[$parameter_key])) {
					$parameter_key++;
					$parameter_key = md5($parameter_key);
				}
				$this->parameters[$parameter_key] = $value;

				$where_opts['value'] = self::PARAMETER_SEPARATOR . $parameter_key;
			}

			$this->where[$where_opts['field']] = [
				'value' => $where_opts['value'],
				'operator' => $where_opts['operator'],
				'raw' => false,
			];
		}

		return $this;
	}

	/**
	 * @param array|string $group_bys
	 *
	 * @return $this
	 */
	public function groupBy($group_bys) {
		if (!is_array($group_bys)) {
			$group_bys = explode(',', $group_bys);
		}

		foreach ($group_bys as &$group_by) {
			$this->doNormaliseTableAndFieldNames($group_by);
		}

		$this->group_by = $group_bys;

		return $this;
	}

	/**
	 * @param string|array $havings
	 *
	 * @return $this
	 *
	 */
	public function having($havings) {
		if (!is_array($havings)) {
			$havings = explode(',', $havings);
		}

		foreach ($havings as &$having) {
			$this->doNormaliseTableAndFieldNames($having);
		}

		$this->having = $havings;

		return $this;
	}

	/**
	 * @param array|string $order_bys
	 *
	 * @return $this
	 */
	public function orderBy($order_bys) {
		if (!is_array($order_bys)) {
			$order_bys = explode(',', $order_bys);
		}

		foreach ($order_bys as &$order_by) {
			$this->doNormaliseTableAndFieldNames($order_by);
		}

		$this->order_by = $order_bys;

		return $this;
	}

	/**
	 * @param $limit
	 *
	 * @return $this
     */
    public function limit($limit) {
		$limit = (int) $limit;
		if ($limit > 0) {
			$this->limit = $limit;
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
	 * @return array
     */
	public function getParameters() {
		return $this->parameters;
	}

	/**
	 * @param string|int|float $value
     */
	public function doStrictTypeValue(&$value) {
		if (is_numeric($value)) {
			if (is_float($value)) {
				$value = (float) $value;

				return;
			} else {
				$int = (int) $value;
				if ($int == $value) {
					$value = $int;

					return;
				}
			}
		}

		$value = (string) $value;
	}
}
