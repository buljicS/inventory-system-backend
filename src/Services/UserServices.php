<?php

declare(strict_types=1);

namespace Services;

use Controllers\DatabaseController as DBController;
use http\Env\Response;
use Slim\Psr7\Request;

class UserServices
{
	private DBController $_database;
	private EmailServices $_email;

	public function __construct(DBController $database, EmailServices $email)
	{
		$this->_email = $email;
		$this->_database = $database;
	}

	public function CreateNewUser(array $userData):void
	{
		$dbCon = $this->_database->OpenConnection();

		$sql = "INSERT INTO workers(
                    workers.worker_fname, 
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

	public function GetUserByEmail(string $email): string | bool {
		$dbCon = $this->_database->OpenConnection();
		$sql = "SELECT worker_password, worker_email, worker_fname
				FROM workers
				WHERE worker_email = :email";


		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':email', $email);
		$stmt->execute();
		return $stmt->fetchColumn();
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
					'description' => "Wrong credentials, please try again!"
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

	public function RegisterUser(array $newUserData): array
	{
		$body = "";
		$newUser = [
			'fname' => strip_tags(trim($newUserData['firstName'])),
			'lname' => strip_tags(trim($newUserData['lastName'])),
			'phone' => strip_tags(trim($newUserData['phoneNumber'])),
			'email' => strip_tags(trim($newUserData['email'])),
			'password' => strip_tags(trim($newUserData['password'])),
			'role' => "Worker",
			'exp_token' => $this->GenerateToken(20),
			'timestamp' => date('Y-m-d H:i:s', time()+3600)
		];

		$doesUserAlreadyExists = $this->GetUserByEmail($newUser['email']);
		if($doesUserAlreadyExists != null) {
			return [
				'status' => '403',
				'message' => 'Forbidden',
				'description' => 'This email is already taken'
			];
		}

		$this->CreateNewUser($newUser);
		$userFromDB = $this->GetUserByEmail($newUser['email']);
		$rawBody = file_get_contents("../email-templates/ActivateAccount.html");
		$body = str_replace("{{userName}}", $userFromDB['fname'], $rawBody);
		$body = str_replace("{{activateAccountLink}}", "www.google.com", $rawBody);
		$isRegistered = $this->SendConfirmationEmail($body,"Activate your account", $newUser['email']);
		if($isRegistered === "Message has been sent") {
			return [
				'status' => '200',
				'message' => 'Success',
				'description' => 'Please check your inbox to activate your account'
			];
		}

		return [
			'status' => '500',
			'message' => 'Internal server error',
			'description' => 'Error while creating your account, please try again'
		];

	}

	public function SendPasswordResetMail(string $emailTo):array
	{
		$cleanEmail = strip_tags(trim($emailTo));
		if(filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
			$body = file_get_contents('../email-templates/ResetMail.html');
			$subject = "Reset your password";
			$cc = null;

			$user = $this->GetUserByEmail($cleanEmail);
			if($user != null) {
				$resp = $this->_email->SendEmail($body, $subject, $cleanEmail, $cc);
				return [
					'status' => '200',
					'message' => 'Success',
					'description' => $resp
				];
			}
		}
		return [
			'status' => '404',
			'message' => 'Not found',
			'description' => 'Please check your email and try again'
		];
	}

	public function SendConfirmationEmail(string $body,string $subject, string $emailTo):string
	{
		return $this->_email->SendEmail($body, $subject, $emailTo, null);
	}

	public function GenerateToken(int $randomBytes):string
	{
		$bytes = random_bytes($randomBytes);
		return bin2hex($bytes);
	}
}