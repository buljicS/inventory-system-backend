<?php

namespace Repositories;

use Controllers\DatabaseController as DBController;
use PDO;

class RoomRepository
{
	private readonly DBController $dbConn;
	public function __construct(DBController $dbConn)
	{
		$this->dbConn = $dbConn;
	}

	public function insertNewRoom(array $newRoom): bool
	{
		$dbConn = $this->dbConn->openConnection();
		$sql = "INSERT INTO rooms (company_id, room_name, room_number, room_description, isActive) VALUE (:company_id, :room_name, :room_number, :room_description, false)";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':company_id', $newRoom['company_id']);
		$stmt->bindParam(':room_name', $newRoom['room_name']);
		$stmt->bindParam(':room_number', $newRoom['room_number']);
		$stmt->bindParam(':room_description', $newRoom['room_description']);
		return $stmt->execute();
	}
}