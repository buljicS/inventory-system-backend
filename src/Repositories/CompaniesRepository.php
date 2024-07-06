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
		$dbCon = $this->database->openConnection();
		$sql = "SELECT company_id, company_name FROM companies WHERE isActive = 1";
		$stmt = $dbCon->prepare($sql);
		if($stmt->execute())
			return $stmt->fetchAll(PDO::FETCH_ASSOC);

		return null;
	}

	public function GetAllCompaniesForAdmin(): ?array
	{
		$dbCon = $this->database->openConnection();
		$sql = "SELECT * FROM companies";
		$stmt = $dbCon->prepare($sql);
		if($stmt->execute())
			return $stmt->fetchAll(PDO::FETCH_ASSOC);

		return null;
	}

	public function insertNewCompany(array $newCompanyData): bool {
		$dbCon = $this->database->openConnection();
		$sql = "INSERT INTO companies (company_name, company_mail, company_address, company_state) VALUES (:company_name, :company_mail, :company_address, :company_state)";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindParam(':company_name', $newCompanyData['company_name']);
		$stmt->bindParam(':company_mail', $newCompanyData['company_mail']);
		$stmt->bindParam(':company_address', $newCompanyData['company_address']);
		$stmt->bindParam(':company_state', $newCompanyData['company_state']);
		if($stmt->execute())
			return true;

		return false;

	}

	public function updateCompany(array $newCompanyData): bool
	{
		$dbCon = $this->database->openConnection();
		$sql = "UPDATE companies SET company_name = :company_name, company_mail = :company_mail , company_address = :company_address, company_state = :company_state WHERE company_id = :id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindParam(':company_name', $newCompanyData['company_name']);
		$stmt->bindParam(':company_mail', $newCompanyData['company_mail']);
		$stmt->bindParam(':company_address', $newCompanyData['company_address']);
		$stmt->bindParam(':company_state', $newCompanyData['company_state']);
		$stmt->bindParam(':id', $newCompanyData['company_id']);
		if($stmt->execute())
			return true;

		return false;
	}

	public function deleteCompany(int $company_id): bool
	{
		$dbCon = $this->database->openConnection();
		$sql = "UPDATE companies SET isActive = 0 WHERE company_id = :company_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindParam(':company_id', $company_id);
		if($stmt->execute())
			return true;

		return false;
	}
}