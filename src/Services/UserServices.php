<?php

declare(strict_types=1);

namespace Services;

use Controllers\DatabaseController as DBController;

class UserServices
{
	public function getAllUsersService(): ?array {
		$dbCon = DBController::openConnection();

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

	public function getSingleUser(int $worker_id): ?array
	{
		$dbCon = DBController::openConnection();

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

	public function authenticateUserService(string $email, string $password): array {
		$dbCon = DBController::openConnection();
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$sql = "SELECT worker_password
					FROM workers
					WHERE worker_email = :email";

			$stmt = $dbCon->prepare($sql);
			$stmt->bindValue(':email', $email);
			$stmt->execute();
			$response = $stmt->fetchColumn(0);

			if ($response == null) {
				return [
					'status' => '404',
					'message' => "No user found"
				];
			}

			if (!password_verify($password, $response)) {
				return [
					'status' => '401',
					'message' => "Email or password wrong"
				];
			}

			return [
				'status' => '200',
				'userEmail' => $email,
				'userPassword' => $response,
				'token' => "1234sssssdddddd"
			];
		}
		return [
			'status' => '401',
			'message' => "Email or password wrong"
		];
	}
}