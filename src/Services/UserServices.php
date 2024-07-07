<?php

declare(strict_types=1);

namespace Services;

use Repositories\CompaniesRepository;
use Repositories\UsersRepository;
use Utilities\MailUtility;
use Utilities\TokenUtility;
use Utilities\ValidatorUtility;
use Services\LogServices as LogServices;

class UserServices
{
	private readonly UsersRepository $userRepo;
	private readonly MailUtility $email;
	private readonly TokenUtility $tokenUtility;
	private readonly LogServices $logServices;
	private readonly ValidatorUtility $validatorUtility;
	private readonly CompaniesRepository $companyRepo;

	public function __construct(MailUtility $email, TokenUtility $tokenUtility, UsersRepository $usersRepository, LogServices $logServices, ValidatorUtility $validatorUtility, CompaniesRepository $companiesRepository)
	{
		$this->email = $email;
		$this->userRepo = $usersRepository;
		$this->tokenUtility = $tokenUtility;
		$this->logServices = $logServices;
		$this->validatorUtility = $validatorUtility;
		$this->companyRepo = $companiesRepository;
	}

	public function registerUser(array $newUserData): array
	{
		$isValid = $this->validatorUtility->validateNewUserData($newUserData);
		if ($isValid !== true) {
			return $isValid;
		}

		$newUserData['password'] = password_hash($newUserData['password'], PASSWORD_DEFAULT);
		$newUserData['role'] = 'Worker';
		$newUserData['exp_token'] = $this->tokenUtility->GenerateBasicToken(20);
		$newUserData['timestamp'] = date('Y-m-d H:i:s', time() + 3600);

		$doesUserAlreadyExists = $this->userRepo->getUserByEmail($newUserData['email']);
		if ($doesUserAlreadyExists != null) {
			return [
				'status' => 403,
				'message' => 'Forbidden',
				'description' => 'This email is already taken'
			];
		}

		if ($this->userRepo->createNewUser($newUserData)) {
			$user_name = $newUserData['firstName'];
			$user_email = $newUserData['email'];
			$token = $newUserData['exp_token'];
			$link = "{$_ENV['MAIN_URL_BE']}api/Users/ActivateUserAccount/$token";

			$sendActMail = $this->sendConfirmationEmail($user_name, $link, "Activate your account", $user_email);
			if ($sendActMail === 'OK') {
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

	public function sendConfirmationEmail(string $user_name, string $link, string $subject, string $emailTo): string
	{
		$rawbody = file_get_contents('../templates/email/ActivateAccount.html');
		$body = str_replace("{{userName}}", $user_name, $rawbody);
		$body = str_replace("{{activateAccountLink}}", $link, $body);

		return $this->email->SendEmail($body, $subject, $emailTo, null);
	}

	public function activateUser(string $token): int
	{
		$user = $this->userRepo->getUserByRegistrationToken($token);
		if ($user === false) return 0;
		if (!(date('Y-m-d H:i:s', time()) > $user['registration_expires'])) {
			$updateResponse = $this->userRepo->activateUser($user['registration_token']);
			if ($updateResponse == "OK")
				return 1;
			else
				return 0;
		}
		return $this->userRepo->deleteUserWithExpiredRegistration($user["registration_token"]);
	}

	public function loginUser(array $loginData): array
	{

		$isValid = $this->validatorUtility->validateLoginUserInput($loginData);
		if ($isValid !== true) {
			return $isValid;
		}

		$response = $this->userRepo->getUserByEmail($loginData['email']);

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

		$isLoggedIn = $loggedIn['status'] == 200 ? 1 : 0;
		$workerId = $response ? $response['worker_id'] : null;
		$note = $loggedIn['status'] != 200 ? $loggedIn['description'] : null;

		$this->logServices->logAccess($isLoggedIn, $workerId, $note);

		return $loggedIn;
	}

	public function sendPasswordResetMail(string $emailTo): array
	{
		$cleanEmail = strip_tags(trim($emailTo));
		if (filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
			$user = $this->userRepo->getUserByEmail($cleanEmail);
			if ($user != null) {
				$token = $this->tokenUtility->GenerateBasicToken(20);
				$expTime = date('Y-m-d H:i:s', time() + 3600);

				$this->userRepo->insertPasswordResetToken($user['worker_id'], $token, $expTime);
				$link = "{$_ENV['MAIN_URL_FE']}/change-password?token=$token";

				$rawBody = file_get_contents('../templates/email/ResetMail.html');
				$body = str_replace("{{userName}}", $user['worker_fname'], $rawBody);
				$body = str_replace("{{resetPasswordLink}}", $link, $body);

				$subject = "Reset your password";
				$cc = null;

				$resp = $this->email->SendEmail($body, $subject, $user['worker_email'], $cc);

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

	public function resetPassword(string $token, string $newPassword): array
	{
		$password = password_hash($newPassword, PASSWORD_DEFAULT);
		$user = $this->userRepo->getUserByPasswordRestToken($token);
		if (!empty($user)) {
			$this->userRepo->updatePassword($user['worker_id'], $password);
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

	public function setNewPassword(array $userInfo): array
	{
		$isValid = $this->validatorUtility->validateNewPasswordData($userInfo);
		if ($isValid !== true) {
			return $isValid;
		}

		$user = $this->userRepo->getUserById($userInfo['worker_id']);

		$checkPasswd = match (true) {
			$user === false => [
				'status' => 404,
				'message' => 'Not found',
				'description' => 'User not found'
			],

			!password_verify($userInfo['old_password'], $user['worker_password']) => [
				'status' => 401,
				'message' => 'Wrong credentials',
				'description' => "Password you provided doesn't match your current password!"
			],

			default => [
				'status' => 200,
				'message' => 'Success'
			]
		};

		if ($checkPasswd['status'] != 200) return $checkPasswd;

		$this->userRepo->updatePassword($userInfo['worker_id'], password_hash($userInfo['new_password'], PASSWORD_DEFAULT));
		return [
			'status' => 200,
			'message' => 'Success',
			'description' => 'Your password has been changed successfully'
		];
	}

	public function getUserInfo(int $user_id): array
	{
		$userInfo = $this->userRepo->getUpdatedUserInfo($user_id);
		$companies = $this->companyRepo->getAllCompaniesForUser();

		if($userInfo === false)
			return [
				'status' => 404,
				'message' => 'Not found',
				'description' => 'User not found'
			];

		return [
			'status' => 200,
			'message' => 'Success',
			'userInfo' => $userInfo,
			'companies' => $companies
		];
	}

	public function updateUserData(array $newUserData): array
	{
		$isValid = $this->validatorUtility->validateUpdatedUserData($newUserData);
		if($isValid !== true) return $isValid;

		$updatedUser = $this->userRepo->updateUser($newUserData);
		if($updatedUser !== false)
			return [
				'status' => 200,
				'message' => 'Success',
				'description' => 'User data has been updated',
				'updatedUser' => $updatedUser
			];

		else
			return [
				'status' => 500,
				'message' => "Internal Server Error",
				'description' => "Failed to updated user"
			];
	}

	public function getAllUsers(): array
	{
		return $this->userRepo->getAllUsersForAdmin();
	}

	public function createNewUser(array $newUser): array
	{
		$isValid = $this->validatorUtility->validateUserToBeAdded($newUser);

		if($isValid !== true) return $isValid;

		$newUser['worker_password'] = password_hash($_ENV['JWT_SECRET'], PASSWORD_DEFAULT);

		$doesUserAlreadyExists = $this->userRepo->getUserByEmail($newUser['worker_email']);
		if ($doesUserAlreadyExists != null) {
			return [
				'status' => 403,
				'message' => 'Forbidden',
				'description' => 'This email is already taken'
			];
		}

		$isUserCreated = $this->userRepo->createNewUserByAdmin($newUser);
		if($isUserCreated === false) {
			return [
				'status' => 500,
				'message' => 'Internal Server Error',
				'description' => 'Error while creating user, please try again'
			];
		}

		$activateUrl = "{$_ENV['MAIN_URL_FE']}/set-new-password?worker_id={$isUserCreated['worker_id']}&old_password={$newUser['worker_password']}&flag=1";
		$subject = "Activate your account";
		$isMailSent = $this->sendConfirmationEmail($newUser['worker_fname'], $activateUrl, $subject, $newUser['worker_email']);

		if($isMailSent === 'OK')
			return [
				'status' => 202,
				'message' => 'Created',
				'description' => 'User has been created, mail with instructions has been sent to user'
			];

		return [
			'status' => 500,
			'message' => 'Internal Server Error',
			'description' => 'Error while creating user, please try again'
		];
	}

	public function changeTempPassword(array $updateData): array
	{
		$isPasswordChanged = $this->userRepo->setPasswordForEmployer($updateData);
		if(!$isPasswordChanged)
			return [
				'status' => 500,
				'message' => 'Internal Server Error',
				'description' => "Failed to change password, please try again"
			];

		return [
			'status' => 200,
			'message' => 'Success',
			'description' => 'Password has been changed successfully'
		];
	}
}