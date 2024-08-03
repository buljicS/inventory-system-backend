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
		$sql = "INSERT INTO tasks (team_id, room_id, start_date, worker_id ,note, isActive) VALUE (:team_id, :room_id, :start_date, :worker_id ,:note, 1)";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':team_id', $newTask['team_id']);
		$stmt->bindParam(':room_id', $newTask['room_id']);
		$stmt->bindParam(':start_date', $newTask['start_date']);
		$stmt->bindParam(':worker_id', $newTask['worker_id']);
		$stmt->bindParam(':note', $newTask['note']);
		return $stmt->execute();
	}

	public function getTaskById(int $task_id): array
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT * FROM tasks WHERE task_id = :task_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':task_id', $task_id);
		$stmt->execute();
		return $stmt->fetch();
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

	public function getRoomByTask(int $task_id): int
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT room_id FROM tasks WHERE task_id = :task_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':task_id', $task_id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}

	public function getScannedItemsForTask(int $task_id): array
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT scanned_item_id FROM scanned_items WHERE task_id = :task_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':task_id', $task_id);
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getScannedItems(int $task_id): array
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT I.item_name, I.serial_no, I.country_of_origin,
					   SI.date_scanned, SI.note AS additional_note,
					   P.picture_path AS additional_picture
				FROM scanned_items SI
         		LEFT JOIN items I on I.item_id = SI.item_id
				LEFT JOIN pictures P on P.picture_id = SI.picture_id
         		WHERE task_id = :task_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':task_id', $task_id);
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getAllTasksByCompany(int $company_id): array
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT room_id FROM rooms WHERE company_id = :company_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':company_id', $company_id);
		$stmt->execute();
		$rooms = $stmt->fetchAll(\PDO::FETCH_FUNC, function ($room) {
			return $room;
		});
		$stmt->closeCursor();
		if(empty($rooms))
			return [];

		$tasks = "SELECT T.task_id, T.start_date, T.note,
       					 TMs.team_name,
       					 R.room_name
       			  FROM tasks T 
       			  LEFT JOIN teams TMs 
       			  	ON TMs.team_id = T.team_id
       			  LEFT JOIN rooms R
       			  	ON R.room_id = T.room_id
         		  WHERE T.room_id IN (" . implode(",", $rooms) . ")";
		$stmt = $dbConn->prepare($tasks);
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function insertTaskResponse(array $taskResponse): bool
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "UPDATE tasks SET summary = :summary, status = :status, end_date = :end_date WHERE task_id = :task_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':task_id', $taskResponse['task_id']);
		$stmt->bindParam(':task_summary', $taskResponse['task_summary']);
		$stmt->bindParam(':status', $taskResponse['status']);
		$end_date = strtotime('now');
		$stmt->bindParam(':end_date', $end_date);
		return $stmt->execute();
	}

	public function getAllTasksForWorker(int $worker_id): array
	{
		$dbConn = $this->dbController->openConnection();
		$teamsQuery = "SELECT team_id FROM team_members WHERE worker_id = :worker_id";
		$stmt = $dbConn->prepare($teamsQuery);
		$stmt->bindParam(':worker_id', $worker_id);
		$stmt->execute();
		$teams = $stmt->fetchAll(\PDO::FETCH_FUNC, function ($team) {
			return $team;
		});
		if(empty($teams))
			return [
				'status' => 404,
				'message' => 'Not found',
				'description' => 'Not member of any team'
			];

		$stmt->closeCursor();
		$tasksQuery = "SELECT 
    					    T.task_id, 
    					    T.start_date,
    					    R.room_name, 
    					    R.room_number,
    					    TMs.team_name,
    					    CONCAT(W.worker_fname, ' ', W.worker_lname) AS created_by
    					FROM tasks T
    					LEFT JOIN rooms R 
    					    ON R.room_id = T.room_id
    					LEFT JOIN teams TMs
    					    ON TMs.team_id = T.team_id
    					RIGHT JOIN workers W
    					    ON W.worker_id = T.worker_id
    					WHERE T.team_id IN (" . implode(",", $teams) . ")";

		$stmt = $dbConn->prepare($tasksQuery);
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
}