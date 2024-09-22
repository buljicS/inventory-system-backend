<?php

declare(strict_types=1);

namespace Services;

use Repositories\CompaniesRepository;
use Repositories\UsersRepository;
use Utilities\MailUtility;
use Utilities\TokenUtility;
use Utilities\ValidatorUtility;
use Services\LogsServices as LogsServices;
use Services\FirebaseServices as FirebaseServices;
use Repositories\TaksRepository as TasksRepository;

class UsersServices
{
	private readonly UsersRepository $userRepo;
	private readonly MailUtility $email;
	private readonly TokenUtility $tokenUtility;
	private readonly LogsServices $logServices;
	private readonly ValidatorUtility $validatorUtility;
	private readonly CompaniesRepository $companyRepo;
	private readonly FirebaseServices $firebaseServices;
	private readonly TasksRepository $tasksRepository;

	public function __construct(MailUtility $email,
								TokenUtility $tokenUtility,
								UsersRepository $usersRepository,
								LogsServices $logServices,
								ValidatorUtility $validatorUtility,
								CompaniesRepository $companiesRepository,
								FirebaseServices $firebaseServices,
								TasksRepository $tasksRepository)
	{
		$this->email = $email;
		$this->userRepo = $usersRepository;
		$this->tokenUtility = $tokenUtility;
		$this->logServices = $logServices;
		$this->validatorUtility = $validatorUtility;
		$this->companyRepo = $companiesRepository;
		$this->firebaseServices = $firebaseServices;
		$this->tasksRepository = $tasksRepository;
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
			$linkBase = str_contains($_ENV['MAIN_URL_BE'], "/") ? $_ENV['MAIN_URL_BE'] : $_ENV['MAIN_URL'] . "/";
			$link = $linkBase . "api/Users/activateUserAccount/$token";

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
		if(empty($response))
			return [
				'status' => 401,
				'message' => 'Unauthorized',
				'description' => "Wrong credentials, please try again!"
			];


		$response['company'] = $this->companyRepo->getCompanyByWorker((int)$response['worker_id']);
		$response['picture'] = $this->userRepo->getUserProfilePicture((int)$response['picture_id']);

		$loggedIn = match (true) {
			!password_verify($loginData['password'], $response['worker_password']) => [
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
				'token' => $this->tokenUtility->GenerateJWTToken($response)
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

	public function uploadUserImage(array $uploadedFiles, int $worker_id): array
	{
		//set base path to local file folder and get uploaded image
		if (!file_exists('tmp'))
			mkdir('tmp', 755);

		$localStoragePath = $_ENV['LOCAL_STORAGE_URL'] . 'tmp';

		$uploadedImage = $uploadedFiles['user_image'];
		if ($uploadedImage->getError() === UPLOAD_ERR_OK) {
			//get basic image info
			$uploadedImageName = $uploadedImage->getClientFileName();
			$fileExt = pathinfo($uploadedImageName, PATHINFO_EXTENSION);
			$mimeType = 'image/' . $fileExt;
			$fullImagePath = $localStoragePath . DIRECTORY_SEPARATOR . $uploadedImageName;
			$encodedImageName = $this->tokenUtility->GenerateBasicToken(16) . "." . $fileExt;

			//move image to temp folder and prepare it for firebase upload
			$uploadedImage->moveTo($fullImagePath);
			$imageToUpload = file_get_contents($fullImagePath);

			//upload file options
			$imageOptions = [
				'file-type' => 1,
				'dir' => 'userPictures/',
				'name' =>  $encodedImageName,
				'mime-type' => $mimeType,
				'predefinedAcl' => 'PUBLICREAD'
			];

			//upload picture to firebase
			$uploadedPicture = $this->firebaseServices->uploadFile($imageToUpload, $imageOptions);
			if($uploadedPicture['status'] == 200) {
				//delete image from temp and return response
				unlink($fullImagePath);
				$uploadedPicture['mime_type'] = $mimeType;
				$uploadedPicture['picture_type_id'] = 1;
				$currentPicture = $this->userRepo->checkUserForPicture($worker_id);
				if($currentPicture != null)
				{
					$this->userRepo->removeUserPicture($worker_id, (int)$currentPicture['picture_id']);
					$this->firebaseServices->deleteFileFromStorage('userPictures', $currentPicture['picture_name']);
				}

				$this->userRepo->saveUserPicture($worker_id, $uploadedPicture);

				return [
					'status' => 202,
					'message' => 'Created',
					'description' => 'Image uploaded successfully',
					'image' => [
						'image_name' => $uploadedPicture['file']['filename'],
						'image_path' => $uploadedPicture['file']['url']
					]
				];
			}
			return $uploadedPicture;
		}

		return [
			'status' => 400,
			'message' => 'Bad request',
			'description' => 'Image upload failed',
			'details' => $uploadedImage->getError()
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

	public function banUser(int $worker_id): array
	{
		$isUserBanned = $this->userRepo->banUser($worker_id);
		if($isUserBanned) {
			$userData = $this->userRepo->getUserById($worker_id);
			$body = file_get_contents('../templates/email/UserBan.html');
			$this->email->SendEmail($body, 'You have been banned', $userData['worker_email'], null);
			return [
				'status' => 200,
				'message' => 'Success',
				'description' => 'User has been banned successfully'
			];
		}
		return [
			'status' => 500,
			'message' => 'Internal Server Error',
			'description' => 'Error while banning user, please try again'
		];
	}

	public function revokeUserAccess(int $worker_id): array
	{
		$isUserRestored = $this->userRepo->revokeUserAccess($worker_id);
		if($isUserRestored) {
			return [
				'status' => 200,
				'message' => 'Success',
				'description' => 'User access has been restored successfully'
			];
		}

		return [
			'status' => 500,
			'message' => 'Internal Server Error',
			'description' => 'Error while restoring user access, please try again'
		];
	}

	public function updateUserByAdmin(array $userData): array {
		$isNewUserDataValid = $this->validatorUtility->validateUpdateUserDataProvidedByAdmin($userData);
		if($isNewUserDataValid !== true) return $isNewUserDataValid;


		$updatedUser = $this->userRepo->updateUserByAdmin($userData);
		if($updatedUser !== false)
			return [
				'status' => 200,
				'message' => 'Success',
				'description' => 'User has been updated'
			];

		return [
			'status' => 500,
			'message' => "Internal Server Error",
			'description' => "Failed to updated user"
		];
	}

	public function deleteUserPicture(int $worker_id, string $userPicture): array
	{
		$isPictureDeleteFromFirebase = $this->firebaseServices->deleteFileFromStorage('userPictures', $userPicture);
		if($isPictureDeleteFromFirebase['status'] == 200) {
			$isPictureDeleted = $this->userRepo->deleteUserPicture($worker_id, $userPicture);
			if($isPictureDeleted !== false)
				return [
					'status' => 200,
					'message' => 'Success',
					'description' => 'Picture has been deleted'
				];
		}

		return [
			'status' => 404,
			'message' => 'Not found',
			'description' => 'Picture not found or it has already been deleted'
		];
	}

	public function enrollUserToTask(int $worker_id, int $task_id): array
	{
		$isUserEnrolled = $this->userRepo->enrollUserToTask($worker_id, $task_id);
		if($isUserEnrolled) {
			$userData = $this->userRepo->getUserById($worker_id);
			$task = $this->tasksRepository->getTaskById($task_id);
			$body = file_get_contents('../templates/email/UserTask.html');
			$body = str_replace('{{userName}}', $userData['worker_fname'], $body);
			$this->email->SendEmail($body, 'You have new task', $userData['worker_email'], null);
			return [
				'status' => 200,
				'message' => 'Success',
				'description' => 'You have been successfully enrolled to this task'
			];
		}

		return [
			'status' => 200,
			'message' => 'Success',
			'description' => 'You are already enrolled to this or other task'
		];
	}

	public function removeUserFromTask(int $worker_id): array
	{
		$isRemoved = $this->userRepo->removeUserFromTask($worker_id);
		if($isRemoved)
			return [
				'status' => 200,
				'message' => 'Success',
				'description' => 'You have been successfully removed from this task'
			];

		return [
			'status' => 200,
			'message' => 'Success',
			'description' => 'You are already removed from this'
		];
	}
}