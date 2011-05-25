<?php
/**
 * file_name  - project_name
 * @author Andre 'Necrotex' Peiffer <necrotex@gmail.com>
 * @version 1.0
 * @package package_name
 */

namespace dioxid\model\engine;

use PDO;
use dioxid\config\Config;
use dioxid\model\engine\PDOEngine;
use dioxid\model\query\Query;

/**
 * dioxid\model\engine$MySQLEngine
 * Baseclass for MySQL Models.
 *
 * @author Andre 'Necrotex' Peiffer <necrotex@gmail.com>
 * @date 20.05.2011 16:50:38
 *
 */
class MySQLEngine extends PDOEngine {

	public static $_tname;

	/**
	 * Method: _init
	 * Constructor. Sets the MySQL specific DSN.
	 *
	 */
	public static function _init($_tname){
		$dsn = 'mysql:dbname='.Config::getVal('database', 'database', true) .
				';host='.Config::getVal('database', 'host', true);

		$options = array(
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
			PDO::MYSQL_ATTR_COMPRESS => true);

		parent::_init($dsn, $options);

		static::$_tname = $_tname;
	}

	protected static function query(){
		return new Query(static::$_tname, &static::$pdo);
	}
}

?>