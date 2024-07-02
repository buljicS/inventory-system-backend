<?php

namespace Repositories;

use Controllers\DatabaseController as DBController;

class AdminRepository
{
	private DBController $database;

	public function __construct(DBController $database)
	{
		$this->database = $database;
	}

	public function GetAdminByEmail(array $credentials)
	{
		$dbConn = $this->database->openConnection();
		$sql = "SELECT * FROM admins WHERE admin_username = :admin_username";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':admin_username', $credentials['email']);
		$stmt->execute();
		return $stmt->fetch();
	}


}