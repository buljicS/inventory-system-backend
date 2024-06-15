<?php

namespace Repositories;

use Controllers\DatabaseController as DBController;
use PDO;

class CompaniesRepository
{
	private DBController $database;
	public function __construct(DBController $database)
	{
		$this->database = $database;
	}

	public function GetAllCompaniesForUser(): ?array
	{
		$dbCon = $this->database->OpenConnection();
		$sql = "SELECT company_id, company_name FROM companies WHERE isActive = 1";
		$stmt = $dbCon->prepare($sql);
		if($stmt->execute())
			return $stmt->fetchAll(PDO::FETCH_ASSOC);

		return null;
	}

	public function GetAllCompaniesForAdmin(): ?array
	{
		$dbCon = $this->database->OpenConnection();
		$sql = "SELECT * FROM companies";
		$stmt = $dbCon->prepare($sql);
		if($stmt->execute())
			return $stmt->fetchAll(PDO::FETCH_ASSOC);

		return null;
	}
}