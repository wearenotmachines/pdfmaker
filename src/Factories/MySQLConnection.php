<?php namespace WeAreNotMachines\PDFMaker\Factories;

use \PDO;

class MySQLConnection {

	private $connection;

	public static $connections = [

		"development" => [
			"host" => "127.0.0.1",
			"username" => "root", 
			"password" => "kuk4hu4a",
			"database" => "pdfmaker_dev"
		],

		"production" => [
			"host" => "127.0.0.1",
			"username" => "root", 
			"password" => "kuk4hu4a",
			"database" => "pdfmaker"
		],

		"testing" => [
			"host" => "127.0.0.1",
			"username" => "root", 
			"password" => "kuk4hu4a",
			"database" => "pdfmaker_test"
		]

	];

	private static $instance;

	protected $defaultConnection = "development";

	private function __construct($connectionData=null) {

		if (empty($connectionData)) {
			$connectionData = self::$connections['development'];
		}

		$this->connection = new PDO("mysql:host=".$connectionData['host'].";dbname=".$connectionData['database'], $connectionData['username'], $connectionData['password']);
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->connection->setAttribute(PDO::ATTR_PERSISTENT, true);
	}

	public function getConnection() {
		return $this->connection;
	}

	public static function getInstance($connection="development") {
		if (empty(self::$instance)) {
			self::$instance = (new MySQLConnection(self::$connections[$connection]))->getConnection();
		}
		return self::$instance;
	}




}