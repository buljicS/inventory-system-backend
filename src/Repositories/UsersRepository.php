<?php

namespace Repositories;

use Controllers\DatabaseController as DBController;

class UsersRepository
{
	private DBController $_database;

	public function __construct(DBController $database)
	{
		$this->_database = $database;
	}

	public function CreateNewUser(array $userData):void
	{
		$dbCon = $this->_database->OpenConnection();

		$sql = "INSERT INTO workers(
                    worker_fname, 
                    worker_lname, 
                    phone_number, 
                    worker_email, 
                    worker_password, 
                    role, 
                    registration_token, 
                    registration_expires) 
					VALUE (
					       :worker_fname, 
					       :worker_lname, 
					       :phone_number, 
					       :worker_email, 
					       :worker_password, 
					       :role, 
					       :registration_token, 
					       :registration_expires
					)";

		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':worker_fname', $userData['fname']);
		$stmt->bindValue(':worker_lname', $userData['lname']);
		$stmt->bindValue(':phone_number', $userData['phone']);
		$stmt->bindValue(':worker_email', $userData['email']);
		$stmt->bindValue(':worker_password', $userData['password']);
		$stmt->bindValue(':role', $userData['role']);
		$stmt->bindValue(':registration_token', $userData['exp_token']);
		$stmt->bindValue(':registration_expires', $userData['timestamp']);
		$stmt->execute();
	}

	public function GetAllUsers(): ?array {
		$dbCon = $this->_database->OpenConnection();

		$sql = "SELECT worker_id, 
       				   worker_fname, 
       				   worker_lname, 
       				   phone_number, 
       				   worker_email, 
       				   picture_id, 
       				   company_id, 
       				   role, 
       				   date_created, 
       				   isActive 
				FROM workers";

		$stmt = $dbCon->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function GetSingleUser(int $worker_id): ?array {
		$dbCon = $this->_database->OpenConnection();

		$sql = "SELECT worker_id, 
       				   worker_fname, 
       				   worker_lname, 
       				   phone_number, 
       				   worker_email, 
       				   picture_id, 
       				   company_id, 
       				   role, 
       				   date_created, 
       				   isActive 
				FROM workers 
				WHERE worker_id = :worker_id";

		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':worker_id', $worker_id);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function GetUserByEmail(string $email): array | bool {
		$dbCon = $this->_database->OpenConnection();
		$sql = "SELECT worker_password,
       				   worker_fname,
       				   worker_email, 
       				   role,
       				   registration_token,
       				   registration_expires
				FROM workers
				WHERE worker_email = :email";


		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':email', $email);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function GetUserByToken(string $token): ?array
	{
		$dbCon = $this->_database->OpenConnection();
		$sql = "SELECT registration_token,
					   worker_id,
       				   registration_expires
       			FROM workers 
       			WHERE registration_token = :token";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':token', $token);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function UpdateUserStatus(string $token): string
	{
		$dbCon = $this->_database->OpenConnection();
		$sql = "UPDATE workers 
				SET isActive = 1
				WHERE registration_token = :token";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':token', $token);
		$stmt->execute();

		$sql_del = "UPDATE workers
					SET registration_token = null,
					    registration_expires = null
					WHERE registration_token = :token";
		$stmt = $dbCon->prepare($sql_del);
		$stmt->bindValue(':token', $token);
		$stmt->execute();

		return "Your account has been activated!";
	}

	public function DeleteExpiredUser(string $token): string
	{
		$dbCon = $this->_database->OpenConnection();
		$sql = "DELETE 
				FROM workers
				WHERE registration_token = :token";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':token', $token);
		$stmt->execute();
		return "Your activation token has expired, please submit registration again";
	}
}