<?php

namespace Bolt\Database;

use Bolt\Exception\Fatal, Bolt\Statics\Setting, Bolt\Exception\Warning, PDO, PDOException;

/**
 * Class Mysql - MySQL database layer, this should be used for all MySQL queries
 *
 * @package Bolt\Database
 */
class Mysql extends Database {

    /**
     * @var null|PDO
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
            try {
                static::$connection = new PDO('mysql:host=' . $server . ';dbname=' . $database, $username, $password);
                static::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new Fatal('Failed to connect to the MySQL server with error: ' . $e->getMessage());
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
     * @return bool
     * @throws \Bolt\Exception\Fatal
     * @throws \Bolt\Exception\Warning
     */
    public function exec($class_name = null) {
        if (static::$connection === null) {
            $this->doConnect(Setting::get('database_server', null, false, false), Setting::get('database_username'), Setting::get('database_password'), Setting::get('database_name'));
        }

        if (empty($this->parameters)) {
            try {
                $result = static::$connection->query($this->getSql());
            } catch (PDOException $e) {
                $result = null;
                throw new Warning('MySQL query error: ' . $e->getMessage());
            }
        } else {
            $statement = static::$connection->prepare($this->getSql());
            foreach ($this->parameters as $parameter => $value) {
                $data_type = PDO::PARAM_STR;
                if (is_float($value)) {
                    // There's no specific type within PDO for Floats currently
                } else if (is_int($value)) {
                    $data_type = PDO::PARAM_INT;
                }
                $statement->bindParam(Database::PARAMETER_SEPARATOR . $parameter, $value, $data_type);
            }

            try {
                $statement->execute();
            } catch (PDOException $e) {
                $result = null;
                throw new Warning('MySQL query error: ' . $e->getMessage());
            }
        }

        return $this->getResultSet($statement, $class_name);
    }

    /**
     * @param \PDOStatement $result
     * @param null|string   $class_name
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
            while ($object = $result->fetchObject($class_name)) {
                $result_set[] = $object;
            }
        }

        return $result_set;
    }

    /**
     * @return string
     */
    protected function getSqlSelectString() {
        $selects = [];
        foreach ($this->select as $select) {
            if (!strstr($select, '.')) {
                // If no derived table name has been specified, then fallback to using the main (FROM) table name
                $selects[] = '`' . $this->from . '`.`' . $select . '`';
            } else {
                $selects[] = '`' . str_replace('.', '`.`', $select) . '`';
            }
        }

        return 'SELECT ' . ($this->format ? "\n\t" : '') . (!empty($selects) ? implode(($this->format ? "\n\t" : '') . ',', $selects) : '`' . $this->from . '`.*');
    }

    /**
     * @return string
     */
    protected function getSqlFromString() {
        return 'FROM ' . ($this->format ? "\n\t" : '') . '`' . $this->from . '`';
    }

    /**
     * @return string
     */
    protected function getSqlWhereString() {
        if (!empty($this->where)) {
            $wheres = [];
            $operator_spacer = ($this->format ? ' ' : '');
            foreach ($this->where as $field => $details) {
                if (!strstr($field, '.')) {
                    $field = '`' . $this->from . '`.`' . $field . '`';
                } else {
                    $field = '`' . str_replace('.', '`.`', $field) . '`';
                }
                if ($details['raw']) {
                    $wheres[] = $field . $details['value'];
                } else {
                    $wheres[] = $field . $operator_spacer . $details['operator'] . $operator_spacer . $details['value'];
                }
            }

            return 'WHERE ' . ($this->format ? "\n\t" : '') . implode(' AND ' . ($this->format ? "\n\t" : '') . '', $wheres);
        }

        return '';
    }

    /**
     * @return string
     */
    protected function getSqlJoinString() {
        if (!empty($this->join)) {
            $joins = [];
            foreach ($this->join as $table => $details) {
                $joins[] = strtoupper($details['type']) . ' JOIN `' . $table . '` ON ' . $details['condition'];
            }

            return implode(' ', $joins);
        }

        return '';
    }

    /**
     * @return string
     */
    protected function getSqlGroupByString() {
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

            return 'GROUP BY ' . ($this->format ? "\n\t" : '') . implode(($this->format ? "\n\t" : '') . ',', $group_by);
        }

        return '';
    }

    /**
     * @return string
     */
    protected function getSqlHavingString() {
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

            return 'HAVING ' . ($this->format ? "\n\t" : '') . implode(($this->format ? "\n\t" : '') . ',', $having);
        }

        return '';
    }


    /**
     * @return string
     */
    protected function getSqlOrderByString() {
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

            return 'ORDER BY ' . ($this->format ? "\n\t" : '') . implode(($this->format ? "\n\t" : '') . ',', $order_bys);
        }

        return '';
    }


    /**
     * @return string
     */
    protected function getSqlLimitString() {
        if ($this->limit > 0) {
            return 'LIMIT ' . (int) $this->limit;
        }

        return '';
    }

    /**
     * @return string
     */
    public function getSql() {
        $sql_parts = [
            $this->getSqlSelectString(),
            $this->getSqlFromString(),
            $this->getSqlWhereString(),
            $this->getSqlJoinString(),
            $this->getSqlGroupByString(),
            $this->getSqlHavingString(),
            $this->getSqlOrderByString(),
            $this->getSqlLimitString(),
        ];

        $sql = '';
        foreach ($sql_parts as $sql_part) {
            if (!empty($sql_part)) {
                if ($this->format) {
                    $sql .= $sql_part . "\n";
                } else {
                    $sql .= ' ' . $sql_part;
                }
            }
        }
        $sql = trim($sql, "\n ");
        $sql .= ';';

        return $sql;
    }
}
