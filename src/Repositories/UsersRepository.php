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

	public function CreateNewUser(array $userData):bool
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
		$dbCon = null;
		return $stmt->execute();

	}

	public function GetUserByEmail(string $email): array | bool {
		$dbCon = $this->_database->OpenConnection();
		$sql = "SELECT worker_id,
    				   worker_password,
       				   worker_fname,
       				   worker_lname,
       				   worker_email, 
       				   role,
       				   registration_token,
       				   registration_expires,
       				   isActive
				FROM workers
				WHERE worker_email = :email";


		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':email', $email);
		$stmt->execute();
		$dbCon = null;
		return $stmt->fetch();
	}

	public function GetUserByRegistrationToken(string $token): ?array
	{
		$dbCon = $this->_database->OpenConnection();
		$sql = "SELECT registration_token, worker_id, registration_expires
       			FROM workers 
       			WHERE registration_token = :token";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':token', $token);
		$stmt->execute();
		$dbCon = null;
		return $stmt->fetch();
	}

	public function GetUserByPasswordRestToken(string $token): ?array
	{
		$dbCon = $this->_database->OpenConnection();
		$sql = "SELECT worker_password, worker_id
				FROM workers
				WHERE forgoten_password_token = :token && forgoten_password_expires <= NOW()";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':token', $token);
		$stmt->execute();
		$dbCon = null;
		return $stmt->fetchAll();
	}

	public function InsertPasswordResetToken(int $worker_id, string $token, mixed $expTime):void
	{
		$dbCon = $this->_database->OpenConnection();
		$sql = "UPDATE workers 
				SET forgoten_password_token = :token,
				    forgoten_password_expires = :expTime
				WHERE worker_id = :worker_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':token', $token);
		$stmt->bindValue(':expTime', $expTime);
		$stmt->bindValue(':worker_id', $worker_id);
		$stmt->execute();
		$dbCon = null;
	}

	public function ActivateUser(string $token): string
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
		$dbCon = null;
		return "OK";
	}

	public function DeleteUserWithExpiredRegistration(string $token): int
	{
		$dbCon = $this->_database->OpenConnection();
		$sql = "DELETE 
				FROM workers
				WHERE registration_token = :token";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':token', $token);
		$stmt->execute();
		$dbCon = null;
		return 0;
	}

	public function UpdatePassword(string $password, int $worker_id):void
	{
		$dbCon = $this->_database->OpenConnection();
		$sql = "UPDATE workers
				SET worker_password = :password, 
				    forgoten_password_token = NULL, 
				    forgoten_password_expires = NULL
				WHERE worker_id = :worker_id";

		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':password', $password);
		$stmt->bindValue(':worker_id', $worker_id);
		$stmt->execute();
		$dbCon = null;
	}
}