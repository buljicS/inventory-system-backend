<?php

namespace Repositories;

use Controllers\DatabaseController as DBController;

class TaksRepository
{
	private readonly DBController $dbController;

	public function __construct(DBController $dbController)
	{
		$this->dbController = $dbController;
	}

	public function insertNewTask(array $newTask): bool
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "INSERT INTO tasks (team_id, room_id, start_date, note, isActive) VALUE (:team_id, :room_id, :start_date, :note, 1)";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':team_id', $newTask['team_id']);
		$stmt->bindParam(':room_id', $newTask['room_id']);
		$stmt->bindParam(':start_date', $newTask['start_date']);
		$stmt->bindParam(':note', $newTask['note']);
		return $stmt->execute();
	}

	public function getAllTasksByRoom(int $room_id): array
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT T.task_id, T.note, T.start_date, 
       				   TMs.team_name,
       				   R.room_name,
       				   R.room_number
				FROM tasks T
         		LEFT JOIN teams TMs 
         		    ON TMs.team_id = T.team_id
                LEFT JOIN rooms R 
                    ON R.room_id = T.room_id        
         		WHERE T.room_id = :room_id
         		ORDER BY T.start_date DESC";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':room_id', $room_id);
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
}