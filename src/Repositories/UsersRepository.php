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

	public function getAllUsersForAdmin(): bool|array
	{
		$dbCon = $this->database->openConnection();

		$sql = "SELECT 
    C.company_name, 
    C.company_id, 
    W.worker_id, 
    W.worker_fname, 
    W.worker_lname, 
    W.worker_email, 
    W.role, 
    W.isActive 
FROM 
    workers W 
LEFT JOIN 
    companies C 
ON 
    C.company_id = W.company_id;";

		$stmt = $dbCon->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function createNewUser(array $userData):bool
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

	public function createNewUserByAdmin(array $userData):bool|array
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
		$stmt->execute();
		if($stmt->rowCount() > 0)
			return $stmt->fetch(PDO::FETCH_ASSOC);

		return false;
	}

	public function getUserById(int $workerId)
	{
		$dbCon = $this->database->openConnection();
		$sql = "SELECT worker_id, worker_email, worker_password FROM workers WHERE worker_id = :worker_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':worker_id', $workerId);
		$stmt->execute();
		return $stmt->fetch();
	}

	public function getUpdatedUserInfo(int $workerId)
	{
		$dbCon = $this->database->openConnection();
		$sql = "SELECT company_id, phone_number FROM workers WHERE worker_id = :worker_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':worker_id', $workerId);
		$stmt->execute();
		return $stmt->fetch();
	}

	public function getUserByEmail(string $email): array | bool {
		$dbCon = $this->database->openConnection();
		$sql = "SELECT worker_id,
    				   worker_password,
       				   worker_fname,
       				   worker_lname,
       				   worker_email,
       				   picture_id,
       				   phone_number,
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

	public function getUserByRegistrationToken(string $token): array|bool
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

	public function getUserByPasswordRestToken(string $token): array|bool
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

	public function insertPasswordResetToken(int $worker_id, string $token, mixed $expTime):void
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

	public function activateUser(string $token): string
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

	public function deleteUserWithExpiredRegistration(string $token): int
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

	public function updatePassword(int $worker_id, string $password):void
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
		$stmt->bindValue(':password', password_hash($updateData['newPassword'], PASSWORD_DEFAULT));
		$stmt->bindValue(':tempPassword', $updateData['oldPassword']);
		$stmt->bindValue(':worker_id', $updateData['worker_id']);
		$stmt->execute();
		return $stmt->rowCount() > 0;
	}

	public function updateUser(array $newUserData): array|bool
	{
		$dbCon = $this->database->openConnection();


		$sql = "UPDATE workers SET phone_number = :phone_number, company_id = :company_id WHERE worker_id = :worker_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindValue(':phone_number', $newUserData['phone_number']);
		$stmt->bindValue(':company_id', $newUserData['company_id']);
		$stmt->bindValue(':worker_id', $newUserData['worker_id']);

		if($stmt->execute())
			return $this->getUpdatedUserInfo($newUserData['worker_id']);
		else
			return false;
	}

	public function banUser(int $worker_id): bool
	{
		$dbCon = $this->database->openConnection();
		$sql = "UPDATE workers SET isActive = 0 WHERE worker_id = :worker_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindParam(':worker_id', $worker_id);
		if($stmt->execute())
			return true;

		return false;
	}

	public function revokeUserAccess(int $worker_id): bool
	{
		$dbCon = $this->database->openConnection();
		$sql = "UPDATE workers SET isActive = 1 WHERE worker_id = :worker_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindParam(':worker_id', $worker_id);
		if ($stmt->execute())
			return true;

		return false;
	}

	public function updateUserByAdmin(array $userData): bool
	{
		$dbCon = $this->database->openConnection();
		$sql = "UPDATE workers SET worker_fname = :worker_fname, worker_lname = :worker_lname, role = :role, company_id = :company_id WHERE worker_id = :worker_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindParam(':worker_id', $userData['worker_id']);
		$stmt->bindParam(':worker_fname', $userData['worker_fname']);
		$stmt->bindParam(':worker_lname', $userData['worker_lname']);
		$stmt->bindParam(':role', $userData['role']);
		$stmt->bindParam(':company_id', $userData['company_id']);
		if ($stmt->execute())
			return true;

		return false;
	}

	public function getUserProfilePicture(int $picture_id): ?array
	{
		if(empty($picture_id))
			return null;

		else {
			$dbCon = $this->database->openConnection();
			$sql = "SELECT picture_path, picture_name FROM pictures WHERE picture_id = :picture_id AND picture_type_id = 1";
			$stmt = $dbCon->prepare($sql);
			$stmt->bindParam(':picture_id', $picture_id);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
	}

	public function checkUserForPicture(int $worker_id): ?array
	{
		$dbCon = $this->database->openConnection();
		$sql = "SELECT picture_id FROM workers WHERE worker_id = :worker_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindParam(':worker_id', $worker_id);
		$stmt->execute();
		$pictureId = $stmt->fetchColumn();
		if($pictureId != null) {
			$stmt->closeCursor();
			$pictureNameQuery = "SELECT picture_name FROM pictures WHERE picture_id = :picture_id";
			$stmt = $dbCon->prepare($pictureNameQuery);
			$stmt->bindParam(':picture_id', $pictureId);
			$stmt->execute();
			$pictureName = $stmt->fetchColumn();
			return [
				'picture_id' => $pictureId,
				'picture_name' => $pictureName
			];
		}
		return null;
	}

	public function saveUserPicture(int $worker_id, array $uploadedPicture): bool
	{
		$dbCon = $this->database->openConnection();
		$pictureId = "SELECT picture_id FROM pictures WHERE picture_name = :picture_name";
		$stmt = $dbCon->prepare($pictureId);
		$stmt->bindParam(':picture_name', $uploadedPicture['file']['filename']);
		$stmt->execute();
		$pictureId = $stmt->fetchColumn();

		$stmt->closeCursor();
		$setUserPicture = "UPDATE workers SET picture_id = :pictureId WHERE worker_id = :worker_id";
		$stmt = $dbCon->prepare($setUserPicture);
		$stmt->bindParam(':worker_id', $worker_id);
		$stmt->bindParam(':pictureId', $pictureId);
		return $stmt->execute();
	}

	public function removeUserPicture(int $worker_id, int $picture_id): bool
	{
		$dbCon = $this->database->openConnection();

		//remove current picture from user
		$removePictureFromUserQuery = "UPDATE workers SET picture_id = NULL WHERE worker_id = :worker_id";
		$stmt = $dbCon->prepare($removePictureFromUserQuery);
		$stmt->bindParam(':worker_id', $worker_id);
		$stmt->execute();
		$stmt->closeCursor();

		$delPictureQuery = "DELETE FROM pictures WHERE picture_id = :picture_id";
		$stmt = $dbCon->prepare($delPictureQuery);
		$stmt->bindParam(':picture_id', $picture_id);
		return $stmt->execute();
	}

	public function deleteUserPicture(int $worker_id, string $userPicture)
	{
		$dbCon = $this->database->openConnection();
		$sql = "UPDATE workers SET picture_id = NULL WHERE worker_id = :worker_id";
		$stmt = $dbCon->prepare($sql);
		$stmt->bindParam(':worker_id', $worker_id);
		$stmt->execute();
		$stmt->closeCursor();

		$pictureId = "DELETE FROM pictures WHERE picture_name = :picture_name";
		$stmt = $dbCon->prepare($pictureId);
		$stmt->bindParam(':picture_name', $userPicture);
		return $stmt->execute();
	}
}