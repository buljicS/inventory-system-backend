<?php

namespace Repositories;

use Controllers\DatabaseController as DBController;
use PDO;

class FirebaseRepository
{
	private readonly DBController $db;
	function __construct(DBController $databaseController)
	{
		$this->database = $databaseController;
	}

	public function saveImage(array $imageProps): bool
	{
		$dbConn = $this->db->openConnection();
		$sql = "INSERT INTO pictures (picture_type_id, picture_name, picture_path, mime_type) VALUES (:picture_type_id, :picture_name, :picture_path, :mime_type)";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':picture_type_id', $imageProps["picture_type"]);
		$stmt->bindParam(':picture_name', $imageProps["name"]);
		$stmt->bindParam(':picture_path', $imageProps["picture_path"]);
		$stmt->bindParam(':mime_type', $imageProps["mime_type"]);
		return $stmt->execute();
	}
}