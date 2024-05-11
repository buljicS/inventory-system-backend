<?php

declare(strict_types=1);

namespace Services;

use Repositories\UsersRepository;
use Utilities\MailUtility;
use Utilities\TokenUtility;
use Valitron\Validator as VValidator;
use Services\LogServices as LogServices;

class UserServices
{
	private UsersRepository $_userRepo;
	private MailUtility $_email;
	private TokenUtility $tokenUtility;
	private LogServices $_logServices;

	public function __construct(MailUtility $email, TokenUtility $tokenUtility, UsersRepository $usersRepository, LogServices $logServices)
	{
		$this->_email = $email;
		$this->_userRepo = $usersRepository;
		$this->tokenUtility = $tokenUtility;
		$this->_logServices = $logServices;
	}

	public function RegisterUser(array $newUserData): array
	{
		#region validation
		$validation = new VValidator($newUserData);
		$validation->rules(
			[
				'required' => [
					['firstName'],
					['lastName'],
					['email'],
					['password']
				],
				'email' => [
					['email']
				]
			]
		);

		if(!$validation->validate()) {
			return [
				'status' => 202,
				'message' => 'Accepted',
				'description' => $validation->errors()
			];
		}
		#endregion

		$newUserData['password'] = password_hash($newUserData['password'], PASSWORD_DEFAULT);
		$newUserData['role'] = 'Worker';
		$newUserData['exp_token'] = $this->tokenUtility->GenerateBasicToken(20);
		$newUserData['timestamp'] = date('Y-m-d H:i:s', time()+3600);

		$doesUserAlreadyExists = $this->_userRepo->GetUserByEmail($newUserData['email']);
		if($doesUserAlreadyExists != null) {
			return [
				'status' => 403,
				'message' => 'Forbidden',
				'description' => 'This email is already taken'
			];
		}

		if($this->_userRepo->CreateNewUser($newUserData)) {
			$user_name = $newUserData['firstName'];
			$user_email = $newUserData['email'];
			$token = $newUserData['exp_token'];
			$link = "{$_ENV['MAIN_URL_BE']}api/Users/ActivateUserAccount/{$token}";
			$sendActMail = $this->SendConfirmationEmail($user_name, $link, "Activate your account", $user_email);
			if($sendActMail === 'OK') {
				return [
					'status' => 200,
					'message' => 'Success',
					'description' => 'Please check your inbox to activate your account'
				];
			}
		}

		return [
			'status' => 500,
			'message' => 'Internal server error',
			'description' => 'Error while creating your account, please try again'
		];
	}

	public function SendConfirmationEmail(string $user_name, string $link, string $subject, string $emailTo):string
	{
		$rawbody = file_get_contents('../templates/email/ActivateAccount.html');
		$body = str_replace("{{userName}}", $user_name, $rawbody);
		$body = str_replace("{{activateAccountLink}}", $link, $body);

		return $this->_email->SendEmail($body, $subject, $emailTo, null);
	}

	public function ActivateUser(string $token):int
	{
		$user = $this->_userRepo->GetUserByRegistrationToken($token);
		if($user === false) return 0;
		if(!(date('Y-m-d H:i:s', time()) > $user['registration_expires'])) {
			$updateResponse = $this->_userRepo->ActivateUser($user['registration_token']);
			if($updateResponse == "OK")
				return 1;
			else
				return 0;
		}
		return $this->_userRepo->DeleteUserWithExpiredRegistration($user["registration_token"]);
	}

	public function LoginUser(array $loginData): array {

		$validation = new VValidator($loginData);
		$validation->rules([
			'required' => [
				['email'],
				['password']
			],
			'email' => [
				['email']
			]
		]);

		if(!$validation->validate()) {
			return [
				'status' => 202,
				'message' => 'Accepted',
				'description' => $validation->errors()
			];
		}


		$response = $this->_userRepo->GetUserByEmail($loginData['email']);

		$loggedIn = match (true) {
			$response === false || !password_verify($loginData['password'], $response['worker_password']) => [
				'status' => 401,
				'message' => 'Unauthorized',
				'description' => "Wrong credentials, please try again!"
			],

			$response['isActive'] != 1 => [
				'status' => 403,
				'message' => 'Forbidden',
				'description' => "Please activate your account!"
			],

			default => [
				'status' => 200,
				'userId' => $response['worker_id'],
				'userFullName' => $response['worker_fname'] . " " . $response['worker_lname'],
				'userEmail' => $response['worker_email'],
				'profilePicture' => null,
				'userRole' => $response['role'],
				'token' => $this->tokenUtility->GenerateJWTToken($response['worker_id'])
			],
		};

		$isLoggedIn = ($loggedIn['status'] == 200);
		$workerId = $response['worker_id'];

		$this->_logServices->LogAccess($isLoggedIn, $workerId);

		return $loggedIn;

	}

	public function SendPasswordResetMail(string $emailTo):array
	{
		$cleanEmail = strip_tags(trim($emailTo));
		if(filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
			$user = $this->_userRepo->GetUserByEmail($cleanEmail);
			if($user != null)
			{
				$token = $this->tokenUtility->GenerateBasicToken(20);
				$expTime = date('Y-m-d H:i:s',time() + 3600);

				$this->_userRepo->InsertPasswordResetToken($user['worker_id'], $token, $expTime);
				$link = "{$_ENV['MAIN_URL_FE']}/change-password?token={$token}";

				$rawBody = file_get_contents('../templates/email/ResetMail.html');
				$body = str_replace("{{userName}}", $user['worker_fname'], $rawBody);
				$body = str_replace("{{resetPasswordLink}}", $link, $body);

				$subject = "Reset your password";
				$cc = null;

				$resp = $this->_email->SendEmail($body, $subject, $user['worker_email'], $cc);

				return [
					'status' => 200,
					'message' => 'Success',
					'description' => $resp
				];
			}
		}
		return [
			'status' => 404,
			'message' => 'Not found',
		];
	}

	public function ResetPassword(string $token, string $newPassword):array
	{
		$password = password_hash($newPassword, PASSWORD_DEFAULT);
		$user = $this->_userRepo->GetUserByPasswordRestToken($token);
		if(!empty($user)) {
			$this->_userRepo->UpdatePassword($password, $user['worker_id']);
			return [
				'status' => 200,
				'message' => 'Success',
				'description' => 'Your password has been changed successfully'
			];
		}

		return [
			'status' => 404,
			'message' => 'Not found',
			'description' => 'This link is no longer active'
		];
	}
}