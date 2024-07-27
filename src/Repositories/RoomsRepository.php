<?php

namespace Repositories;

use Controllers\DatabaseController as DBController;
use PDO;

class RoomsRepository
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

	public function getAllRooms(): ?array
	{
		$dbConn = $this->dbConn->openConnection();
		$sql = "SELECT * FROM rooms";
		$stmt = $dbConn->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getRoomByCompanyId(int $company_id): ?array
	{
		$dbConn = $this->dbConn->openConnection();
		$sql = "SELECT * FROM rooms WHERE company_id = :company_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':company_id', $company_id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function deleteRoom(int $room_id): bool
	{
		$dbConn = $this->dbConn->openConnection();
		$sql = "DELETE FROM rooms WHERE room_id = :room_id AND isActive = 0";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':room_id', $room_id);
		$stmt->execute();
		return $stmt->rowCount() > 0;
	}

	public function updateRoom(array $updatedRoom): bool
	{
		$dbConn = $this->dbConn->openConnection();
		$sql = "UPDATE rooms SET room_name = :room_name, room_number = :room_number, room_description = :room_description, isActive = :isActive WHERE room_id = :room_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':room_id', $updatedRoom['room_id']);
		$stmt->bindParam(':room_name', $updatedRoom['room_name']);
		$stmt->bindParam(':room_number', $updatedRoom['room_number']);
		$stmt->bindParam(':room_description', $updatedRoom['room_description']);
		$stmt->bindParam(':isActive', $updatedRoom['isActive']);
		$stmt->execute();
		return $stmt->rowCount() > 0;
	}

	public function getRoomName(int $room_id): string
	{
		$dbConn = $this->dbConn->openConnection();
		$sql = "SELECT room_name FROM rooms WHERE room_id = :room_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':room_id', $room_id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
}