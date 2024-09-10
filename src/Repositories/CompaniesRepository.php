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

	public function getAllCompaniesForUser(): ?array
	{
		$dbCon = $this->database->openConnection();
		$sql = "SELECT company_id, company_name, isActive FROM companies WHERE isActive = 1";
		$stmt = $dbCon->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAllCompaniesForAdmin(): ?array
	{
		$dbCon = $this->database->openConnection();
		$sql = "SELECT * FROM companies";
		$stmt = $dbCon->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function insertNewCompany(array $newCompanyData): bool
	{
		$dbCon = $this->database->openConnection();
		$sql = "INSERT INTO companies (company_name, company_mail, company_address, company_state) VALUES (:company_name, :company_mail, :company_address, :company_state)";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindParam(':company_name', $newCompanyData['company_name']);
		$stmt->bindParam(':company_mail', $newCompanyData['company_mail']);
		$stmt->bindParam(':company_address', $newCompanyData['company_address']);
		$stmt->bindParam(':company_state', $newCompanyData['company_state']);
		return $stmt->execute();
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
		return $stmt->execute();
	}

	public function deleteCompany(int $company_id): bool
	{
		$dbCon = $this->database->openConnection();
		$sql = "UPDATE companies SET isActive = 0 WHERE company_id = :company_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindParam(':company_id', $company_id);
		$stmt->execute();
		return $stmt->rowCount() > 0;
	}

	public function restoreCompany(int $company_id): bool
	{
		$dbCon = $this->database->openConnection();
		$sql = "UPDATE companies SET isActive = 1 WHERE company_id = :company_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
		return $stmt->execute();
	}

	public function getCompanyByWorker(int $worker_id): array|bool
	{
		$dbCon = $this->database->openConnection();
		$sql = "SELECT W.company_id, C.company_name FROM workers W
                LEFT JOIN companies C ON C.company_id = W.company_id
                WHERE W.worker_id = :worker_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindParam(':worker_id', $worker_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function getCompanyById(int $company_id): array|bool
	{
		$dbCon = $this->database->openConnection();
		$sql = "SELECT company_name, company_address, company_mail 
				FROM companies 
				WHERE company_id = :company_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
}