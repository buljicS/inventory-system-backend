<?php

namespace Repositories;

use Controllers\DatabaseController as DBController;
use PDO;

class UsersRepository
{
	private DBController $database;

	public function __construct(DBController $database)
	{
		$this->database = $database;
	}

	public function GetAllUsersForAdmin(): bool|array
	{
		$dbCon = $this->database->openConnection();

		$sql = "SELECT worker_id, 
       				   worker_fname, 
       				   worker_lname, 
       				   worker_email, 
       				   phone_number, 
       				   date_created, 
       				   role, 
       				   isActive 
			    FROM workers";

		$stmt = $dbCon->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function CreateNewUser(array $userData):bool
	{
		$dbCon = $this->database->openConnection();

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

	public function CreateNewUserByAdmin(array $userData):bool|array
	{
		$dbCon = $this->database->openConnection();

		$sql = "INSERT INTO workers(
                    worker_fname, 
                    worker_lname, 
                    worker_email, 
                    worker_password, 
                    role,
                    company_id,
                    isActive) 
					VALUE (
					       :worker_fname, 
					       :worker_lname, 
					       :worker_email, 
					       :worker_password, 
					       :role, 
					       :company_id,
					       :is_active
					)";

		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':worker_fname', $userData['worker_fname']);
		$stmt->bindValue(':worker_lname', $userData['worker_lname']);
		$stmt->bindValue(':worker_email', $userData['worker_email']);
		$stmt->bindValue(':worker_password', $userData['worker_password']);
		$stmt->bindValue(':role', "employer");
		$stmt->bindValue(':company_id', $userData['company_id']);
		$stmt->bindValue(':is_active', false);
		$stmt->execute();

		$newUser = "SELECT worker_id FROM workers WHERE worker_email = :worker_email";
		$stmt = $dbCon->prepare($newUser);
		$stmt->bindValue(':worker_email', $userData['worker_email']);
		if($stmt->execute())
			return $stmt->fetch(PDO::FETCH_ASSOC);

		return false;
	}

	public function GetUserById(int $workerId)
	{
		$dbCon = $this->database->openConnection();
		$sql = "SELECT worker_id, worker_email, worker_password FROM workers WHERE worker_id = :worker_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':worker_id', $workerId);
		$stmt->execute();
		return $stmt->fetch();
	}

	public function GetUpdatedUserInfo(int $workerId)
	{
		$dbCon = $this->database->openConnection();
		$sql = "SELECT company_id, phone_number FROM workers WHERE worker_id = :worker_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':worker_id', $workerId);
		$stmt->execute();
		return $stmt->fetch();
	}

	public function GetUserByEmail(string $email): array | bool {
		$dbCon = $this->database->openConnection();
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
		$dbCon = $this->database->openConnection();
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
		$dbCon = $this->database->openConnection();
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
		$dbCon = $this->database->openConnection();
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
		$dbCon = $this->database->openConnection();
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
		$dbCon = $this->database->openConnection();
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
		$dbCon = $this->database->openConnection();
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

	public function setPasswordForEmployer(array $updateData): bool
	{
		$dbCon = $this->database->openConnection();
		$sql = "UPDATE workers SET worker_password = :password, isActive = 1 WHERE worker_password = :tempPassword AND worker_id = :worker_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':password', $updateData['newPassword']);
		$stmt->bindValue(':tempPassword', $updateData['oldPassword']);
		$stmt->bindValue(':worker_id', $updateData['worker_id']);
		return $stmt->execute();
	}

	public function UpdateUser(array $newUserData): array|bool
	{
		$dbCon = $this->database->openConnection();


		$sql = "UPDATE workers SET phone_number = :phone_number, company_id = :company_id WHERE worker_id = :worker_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':phone_number', $newUserData['phone_number']);
		$stmt->bindValue(':company_id', $newUserData['company_id']);
		$stmt->bindValue(':worker_id', $newUserData['worker_id']);

		if($stmt->execute())
			return $this->GetUpdatedUserInfo($newUserData['worker_id']);
		else
			return false;
	}


}