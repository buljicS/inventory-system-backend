<?php

declare(strict_types=1);

namespace Services;

use Controllers\DatabaseController as DBController;
use Exception;

class UserServices
{
	private DBController $_database;

	public function __construct(DBController $database)
	{
		$this->_database = $database;
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

	public function GetUserByEmail(string $email): string | bool {
		$dbCon = $this->_database->OpenConnection();
		$sql = "SELECT worker_password
				FROM workers
				WHERE worker_email = :email 
				  AND isActive = true";

		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':email', $email);
		$stmt->execute();
		return $stmt->fetchColumn(0);
	}

	public function AuthenticateUser(string $email, string $password): array {

		$cleanEmail = strip_tags(trim($email));
		$cleanPassword = strip_tags(trim($password));

		if(filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
			$response = $this->GetUserByEmail($cleanEmail);
			if ($response == null) {
				return [
					'status' => '404',
					'message' => 'Not found',
					'description' => "No user found"
				];
			}

			if (!password_verify($cleanPassword, $response)) {
				return [
					'status' => '401',
					'message' => 'Unauthorized',
					'description' => "Wrong credentials, please try again!"
				];
			}

			return [
				'status' => '200',
				'userEmail' => $cleanEmail,
				'userPassword' => $response,
				//TODO implement firebase/jwt
				'token' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c"
			];
		}
		return [
			'status' => '401',
			'message' => 'Unauthorized',
			'description' => "Wrong credentials, please try again!"
		];
	}
}