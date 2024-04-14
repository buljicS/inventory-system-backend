<?php

declare(strict_types=1);

namespace Services;

use Controllers\DatabaseController as DBController;
use Controllers\HelperController as HelperController;
use PDO;
use Repositories\UsersRepository;
use Services\EmailServices as EmailServices;

class UserServices
{

	private UsersRepository $_userRepo;
	private EmailServices $_email;
	private HelperController $_helper;

	public function __construct(EmailServices $email, HelperController $helper, UsersRepository $usersRepository)
	{
		$this->_email = $email;
		$this->_userRepo = $usersRepository;
		$this->_helper = $helper;
	}

	public function AuthenticateUser(string $email, string $password): array {

		$cleanEmail = strip_tags(trim($email));
		$cleanPassword = strip_tags(trim($password));

		if(filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
			$response = $this->_userRepo->GetUserByEmail($cleanEmail);
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
			'exp_token' => $this->_helper->GenerateBasicToken(20),
			'timestamp' => date('Y-m-d H:i:s', time()+3600)
		];

		$doesUserAlreadyExists = $this->_userRepo->GetUserByEmail($newUser['email']);
		if($doesUserAlreadyExists != null) {
			return [
				'status' => '403',
				'message' => 'Forbidden',
				'description' => 'This email is already taken'
			];
		}

		$this->_userRepo->CreateNewUser($newUser);
		$user = $this->_userRepo->GetUserByEmail($newUser['email']);
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
			$user = $this->_userRepo->GetUserByEmail($cleanEmail);
			if($user != null)
			{
				$rawBody = file_get_contents('../templates/email/ResetMail.html');
				$body = str_replace("{{userName}}", $user[0]['worker_fname'], $rawBody);
				$link = "{$_ENV['MAIN_URL_BE']}api/Users/ResetPassword/{$user[0]['worker_password']}";
				$body = str_replace("{{resetPasswordLink}}", $link, $body);
				$subject = "Reset your password";
				$cc = null;

				$resp = $this->_email->SendEmail($body, $subject, $user[0]['worker_email'], $cc);
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
		$rawbody = file_get_contents('../email/ActivateAccount.html');
		$body = str_replace("{{userName}}", $user_name, $rawbody);
		$body = str_replace("{{activateAccountLink}}", $link, $body);

		return $this->_email->SendEmail($body, $subject, $emailTo, null);
	}

	public function ActivateUser(string $token):int
	{
		$user = $this->_userRepo->GetUserByToken($token);
		if($user != null && !(date('d-M-Y H:i:s', time()) > $user[0]['registration_expires'])) {
			$updateResponse = $this->_userRepo->UpdateUserStatus($user[0]['registration_token']);
			if($updateResponse != null)
				return 1;
		}
		$delResp =  $this->_userRepo->DeleteExpiredUser($user[0]["registration_token"]);
		return 0;
	}

	public function ResetPassword(string $hash, string $newPassword):int
	{
		$password = password_hash($newPassword, PASSWORD_DEFAULT);
		$user = $this->_userRepo->GetUserByHash($hash);
		if($user != null) {
			$this->_userRepo->UpdatePassword($password, $user[0]['worker_id']);
			return 1;
		}
		return 0;
	}
}