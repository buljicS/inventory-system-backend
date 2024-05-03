<?php

namespace Repositories;

use Controllers\DatabaseController as DBController;
use PDO;

class LogRepository
{
	private DBController $_database;
	public function __construct(DBController $database)
	{
		$this->_database = $database;
	}

	public function GetAllLogs(): ?array
	{
		$conn = $this->_database->OpenConnection();
		$sql = "SELECT * FROM access_logs";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


}