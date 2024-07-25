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

	public function saveFile(array $fileOptions): bool
	{
		$dbConn = $this->db->openConnection();
		$sql = "INSERT INTO pictures (picture_type_id, picture_name, picture_path, mime_type) VALUES (:picture_type_id, :picture_name, :picture_path, :mime_type)";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':picture_type_id', $fileOptions["file-type"]);
		$stmt->bindParam(':picture_name', $fileOptions["name"]);
		$stmt->bindParam(':picture_path', $fileOptions["file_path"]);
		$stmt->bindParam(':mime_type', $fileOptions["mime-type"]);
		return $stmt->execute();
	}
}