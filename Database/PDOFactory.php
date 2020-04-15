<?php
namespace NoMess\Database;


class PDOFactory implements IPDOFactory
{

	private const DATA = 'App/config/database.php';

	/**
	 * Insatnce de PDO
	 *
	 * @var \PDO
	 */
	private $instance;

	/**
	 * Administre les instances
	 *
	 * @return \PDO
	 */
	public function getConnection() : \PDO
	{
		if($this->instance === null){
			$this->createConnection();
		}
		
		return $this->instance;
	}

	/**
	 * Initialise une connection
	 *
	 * @return void
	 */
	private function createConnection() : void
	{
		$tab = require_once self::DATA;

		$db = new \PDO('mysql:host=' . $tab['host'] . ';dbname=' . $tab['dbname'] . '', $data['user'], $data['password'], array(
				\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
		));

		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		$this->instance = $db;

	}
}