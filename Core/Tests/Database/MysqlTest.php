<?php
namespace Core\Tests\Database;

/**
 * Class MysqlTest
 *
 * @package Core\Tests\Database
 */
class MysqlTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */
	public function testWhere() {
		$db = $this->getDatabaseInstance();
		$sql = $db->select(['test'])
			->from('table')
			->where('live = 1 AND deleted = 0')
			->getSql();

		$this->assertEquals('SELECT `table`.`test`
FROM `table`
WHERE `table`.`live`=:' . md5('live') . ' AND `table`.`deleted`=:' . md5('deleted') . ';', $sql);
	}

	/**
	 * @return \Core\Database\Mysql
	 */
	private function getDatabaseInstance() {
		return new \Core\Database\Mysql();
	}
}