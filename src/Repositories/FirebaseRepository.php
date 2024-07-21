<?php

namespace Repositories;

use Controllers\DatabaseController as DBController;
use PDO;

class FirebaseRepository
{
	private readonly DBController $db;
	function __construct(DBController $databaseController)
	{
		$this->db = $databaseController;
	}

	public function saveImage(array $imageProps, int $worker_id): bool
	{
		$dbConn = $this->db->openConnection();
		$sql = "INSERT INTO pictures (picture_type_id, picture_name, picture_path, mime_type) VALUES (:picture_type_id, :picture_name, :picture_path, :mime_type)";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':picture_type_id', $imageProps["picture_type"]);
		$stmt->bindParam(':picture_name', $imageProps["name"]);
		$stmt->bindParam(':picture_path', $imageProps["picture_path"]);
		$stmt->bindParam(':mime_type', $imageProps["type"]);
		$stmt->execute();
		$stmt->closeCursor();

		//fetch image id
		$imageIdQuery = "SELECT picture_id FROM pictures WHERE picture_name = :picture_name";
		$stmt = $dbConn->prepare($imageIdQuery);
		$stmt->bindParam(':picture_name', $imageProps['encoded_name']);
		$stmt->execute();
		$imageId = $stmt->fetch();
		$stmt->closeCursor();

		//set it to new user
		$updateUserQuery = "UPDATE workers SET picture_id = :picture_id WHERE worker_id = :worker_id";
		$stmt = $dbConn->prepare($updateUserQuery);
		$stmt->bindParam(':picture_id', $imageId["picture_id"]);
		$stmt->bindParam(':worker_id', $worker_id);
		return $stmt->execute();
	}
}