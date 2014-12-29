<?php
namespace Bolt\Tests\Database;

use Bolt\Database\Mysql;

/**
 * Class MysqlTest
 *
 * @package Bolt\Tests\Database
 */
class MysqlTest extends \PHPUnit_Framework_TestCase {

    /**
     *
     */
    public function testSelect() {
        $expected_sql = 'SELECT `table`.`col1`,`table`.`col2`,`table`.`col3` FROM `table`;';

        // Test every possible format for select clause
        $format_variants = [
            'col1, col2, col3', // Pure string with no derived table name
            ['col1', 'col2', 'col3'], // Array with no derived table name

            'table.col1, table.col2, table.col3', // Pure string with a derived table name
            ['table.col1', 'table.col2', 'table.col3'], // Array with a derived table name
        ];
        foreach ($format_variants as $variant) {
            $db = $this->getDatabaseInstance();
            $sql = $db->select($variant)->from('table')->getSql();

            // Test the auto generated SQL output
            $this->assertEquals($expected_sql, $sql);
        }
    }

    /**
     *
     */
    public function testFrom() {
        $db = $this->getDatabaseInstance();
        $this->assertEquals('SELECT `table`.* FROM `table`;', $db->from('table')->getSql());
    }

    /**
     *
     */
    public function testGroupBy() {
        $db = $this->getDatabaseInstance();
        $this->assertEquals('SELECT `table`.* FROM `table` GROUP BY `table`.`test`;', $db->from('table')->groupBy('test')->getSql());
        $this->assertEquals('SELECT `table`.* FROM `table` GROUP BY `table`.`test`;', $db->from('table')->groupBy(['test'])->getSql());
        $this->assertEquals('SELECT `table`.* FROM `table` GROUP BY `table`.`test`;', $db->from('table')->groupBy(['table.test'])->getSql());
    }

    /**
     *
     */
    public function testHaving() {
        $db = $this->getDatabaseInstance();
        $this->assertEquals('SELECT `table`.* FROM `table` HAVING `table`.`test`;', $db->from('table')->having('test')->getSql());
        $this->assertEquals('SELECT `table`.* FROM `table` HAVING `table`.`test`;', $db->from('table')->having(['test'])->getSql());
        $this->assertEquals('SELECT `table`.* FROM `table` HAVING `table`.`test`;', $db->from('table')->having(['table.test'])->getSql());
    }

    /**
     *
     */
    public function testOrderBy() {
        $db = $this->getDatabaseInstance();
        $this->assertEquals('SELECT `table`.* FROM `table` ORDER BY `table`.`test`;', $db->from('table')->orderBy('test')->getSql());
        $this->assertEquals('SELECT `table`.* FROM `table` ORDER BY `table`.`test`;', $db->from('table')->orderBy(['test'])->getSql());
        $this->assertEquals('SELECT `table`.* FROM `table` ORDER BY `table`.`test`;', $db->from('table')->orderBy(['table.test'])->getSql());
    }

    /**
     *
     */
    public function testLimit() {
        $db = $this->getDatabaseInstance();
        $this->assertEquals('SELECT `table`.* FROM `table` LIMIT 50;', $db->from('table')->limit(50)->getSql());

        $db = $this->getDatabaseInstance();
        $this->assertEquals('SELECT `table`.* FROM `table`;', $db->from('table')->getSql());
    }

    /**
     *
     */
    public function testWhere() {
        $expected_sql = 'SELECT `table`.`test` FROM `table` WHERE `table`.`live`=:' . md5('live') . ' AND `table`.`deleted`=:' . md5('deleted') . ';';

        // Test every possible format for a where clause to ensure it's auto-generated integrity matching against the above valid SQL string
        $where_format_variants = [
            'live = 1 AND deleted = 0', // Pure string
            ['live' => 1, 'deleted' => 0], // Array using key value pairs with integer values
            ['live' => '1', 'deleted' => '0'], // Array using key value pairs with numeric string values
            ['live = 1', 'deleted = 0'], // Array using numeric indexes with full key/value pair within the array value

            'live = \'1\' AND deleted = \'0\'', // Check quoted values are escaped and still equate to the same SQL
            'live=\'1\' AND deleted=\'0\'', // Check whitespace is correctly catered for
        ];
        foreach ($where_format_variants as $where) {
            $db = $this->getDatabaseInstance();
            $this->assertEquals($expected_sql, $db->select(['test'])->from('table')->where($where)->getSql());
            $this->assertEquals($db->get_parameters(), [
                md5('live') => 1,
                md5('deleted') => 0,
            ]);
        }

        // Test various different operators
        $db = $this->getDatabaseInstance();
        $this->assertEquals('SELECT `table`.`test` FROM `table` WHERE `table`.`col1`!=:' . md5('col1') . ' AND `table`.`col2`=:' . md5('col2') . ' AND `table`.`col3`<>:' . md5('col3') . ' AND `table`.`col4`>:' . md5('col4') . ';', $db->select(['test'])->from('table')->where([
            'col1 != col1',
            'col2' => 'col2',
            'col3 <> col3',
            'col4 > col4',
        ])->getSql());
        $this->assertEquals($db->get_parameters(), [
            md5('col1') => 'col1',
            md5('col2') => 'col2',
            md5('col3') => 'col3',
            md5('col4') => 'col4',
        ]);

        // Test manual parameterized where queries
        $db = $this->getDatabaseInstance();
        $sql = $db->select(['test'])->from('table')->where('live = :live AND deleted = :deleted', ['live' => 1, 'deleted' => 0])->getSql();
        $this->assertEquals('SELECT `table`.`test` FROM `table` WHERE `table`.`live`=:live AND `table`.`deleted`=:deleted;', $sql);
        $this->assertEquals($db->get_parameters(), [
            'live' => 1,
            'deleted' => 0,
        ]);
    }

    /**
     * @return Mysql
     */
    private function getDatabaseInstance() {
        return new Mysql();
    }
}