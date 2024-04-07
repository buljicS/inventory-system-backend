<?php

declare(strict_types=1);

namespace Services;

use Controllers\DatabaseController as DBController;
use Controllers\HelperController as HelperController;
use PDO;
use Services\EmailServices as EmailServices;

class UserServices
{
	private DBController $_database;
	private EmailServices $_email;
	private HelperController $_helper;

	public function __construct(DBController $database, EmailServices $email, HelperController $helper)
	{
		$this->_email = $email;
		$this->_database = $database;
		$this->_helper = $helper;
	}

	#region DBManipulation
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
				WHERE registration_token = :$token";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':token', $token);
		$stmt->execute();
		return "Your activation token has expired, please submit registration again";
	}
	#endregion



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

			if (!password_verify($cleanPassword, $response[0]['worker_password'])) {
				return [
					'status' => '401',
					'message' => 'Unauthorized',
					'description' => "Wrong credentials, please try again!"
				];
			}

			return [
				'status' => '200',
				'userEmail' => $cleanEmail,
				'userPassword' => 1234567890,
				'token' => $this->_helper->GenerateJWTToken("buljic77@gmail.com", "worker")
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
		$cleanPassword = strip_tags(trim($newUserData['password']));
		$newUser = [
			'fname' => strip_tags(trim($newUserData['firstName'])),
			'lname' => strip_tags(trim($newUserData['lastName'])),
			'phone' => strip_tags(trim($newUserData['phoneNumber'])),
			'email' => strip_tags(trim($newUserData['email'])),
			'password' => password_hash($cleanPassword, PASSWORD_DEFAULT),
			'role' => "Worker",
			'exp_token' => $this->_helper->GenerateRegistrationToken(20),
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
		$user = $this->GetUserByEmail($newUser['email']);
		$user_name = $user[0]['worker_fname'];
		$userEmail = $user[0]['worker_email'];
		$token = $user[0]['registration_token'];
		$link = "{$_ENV['MAIN_URL_BE']}api/Users/ActivateUserAccount/{$token}";
		$isRegistered = $this->SendConfirmationEmail($user_name, $link, "Activate your account", $userEmail);
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

	public function SendConfirmationEmail(string $user_name, string $link, string $subject, string $emailTo):string
	{
		$rawbody = file_get_contents('../email-templates/ActivateAccount.html');
		$body = str_replace("{{userName}}", $user_name, $rawbody);
		$body = str_replace("{{activateAccountLink}}", $link, $body);

		return $this->_email->SendEmail($body, $subject, $emailTo, null);
	}

	public function ActivateUser(string $token):int
	{
		$user = $this->GetUserByToken($token);
		if($user != null && !(date('d-M-Y H:i:s', time()) > $user[0]['registration_expires'])) {
			$updateResponse = $this->UpdateUserStatus($user[0]['registration_token']);
			if($updateResponse != null)
				return 1;
		}
		$delResp =  $this->DeleteExpiredUser($user[0]["registration_token"]);
		if($delResp != null) {
			return 0;
		}
	}
}