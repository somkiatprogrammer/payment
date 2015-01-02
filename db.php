<?php
class DB {
	private $conn;
	function __construct($server, $username, $password, $dbname) {
		$this->conn = new PDO ( 'mysql:host=' . $server . ';dbname=' . $dbname . ';charset=utf8', $username, $password ) or DIE ( "Can not connect DB." );
		$this->conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$this->conn->setAttribute ( PDO::ATTR_EMULATE_PREPARES, false );
	}
	public function save($values) {
		try {
			$stmt = $this->conn->prepare ( "INSERT INTO `payment`(`tran_id`, `amount`, `currency`, `create_time`, `shipping_name`) VALUES(?, ?, ?, ?, ?)" );
			
			return $stmt->execute ( array (
					$values ['tran_id'],
					$values ['amount'],
					$values ['currency'],
					$values ['create_time'],
					$values ['shipping_name'] 
			) );
		} catch ( Exception $e ) {
			throw new \Exception ( $e->getMessage () );
		}
	}
}