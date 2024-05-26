<?php

namespace Repositories;

use Controllers\DatabaseController as DBController;

class UsersRepository
{
	private DBController $database;

	public function __construct(DBController $database)
	{
		$this->database = $database;
	}

	public function CreateNewUser(array $userData):bool
	{
		$dbCon = $this->database->OpenConnection();

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
		$stmt->bindValue(':worker_fname', $userData['firstName']);
		$stmt->bindValue(':worker_lname', $userData['lastName']);
		$stmt->bindValue(':phone_number', $userData['phoneNumber']);
		$stmt->bindValue(':worker_email', $userData['email']);
		$stmt->bindValue(':worker_password', $userData['password']);
		$stmt->bindValue(':role', $userData['role']);
		$stmt->bindValue(':registration_token', $userData['exp_token']);
		$stmt->bindValue(':registration_expires', $userData['timestamp']);
		$dbCon = null;
		return $stmt->execute();

	}

	public function GetUserById(int $workerId)
	{
		$dbCon = $this->database->OpenConnection();
		$sql = "SELECT worker_id, worker_email, worker_password FROM workers WHERE worker_id = :worker_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':worker_id', $workerId);
		$stmt->execute();
		return $stmt->fetch();
	}

	public function GetUserByEmail(string $email): array | bool {
		$dbCon = $this->database->OpenConnection();
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

	public function GetUserByRegistrationToken(string $token): array|bool
	{
		$dbCon = $this->database->OpenConnection();
		$sql = "SELECT registration_token, worker_id, registration_expires
       			FROM workers 
       			WHERE registration_token = :token";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':token', $token);
		$stmt->execute();
		$dbCon = null;
		return $stmt->fetch();
	}

	public function GetUserByPasswordRestToken(string $token): array|bool
	{
		$dbCon = $this->database->OpenConnection();
		$sql = "SELECT worker_password, worker_id
				FROM workers
				WHERE forgoten_password_token = :token && forgoten_password_expires >= NOW()";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':token', $token);
		$stmt->execute();
		$dbCon = null;
		return $stmt->fetch();
	}

	public function InsertPasswordResetToken(int $worker_id, string $token, mixed $expTime):void
	{
		$dbCon = $this->database->OpenConnection();
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
		$dbCon = $this->database->OpenConnection();
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
		$dbCon = $this->database->OpenConnection();
		$sql = "DELETE 
				FROM workers
				WHERE registration_token = :token";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':token', $token);
		$stmt->execute();
		$dbCon = null;
		return 0;
	}

	public function UpdatePassword(int $worker_id, string $password):void
	{
		$dbCon = $this->database->OpenConnection();
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