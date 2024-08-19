<?php

namespace Repositories;

use Controllers\DatabaseController as DBController;
use Repositories\RoomsRepository as RoomsRepository;
use PDO;

class TaksRepository
{
	private readonly DBController $dbController;
	private readonly RoomsRepository $roomsRepository;

	public function __construct(DBController $dbController, RoomsRepository $roomsRepository)
	{
		$this->dbController = $dbController;
		$this->roomsRepository = $roomsRepository;
	}

	public function insertNewTask(array $newTask): array|bool
	{
		$currentTasks = $this->getTasksByRoom($newTask['room_id']);
		if(!empty($currentTasks)) {
			for($i = 0; $i < count($currentTasks); $i++) {
				$currentTaskTime = new \DateTime($currentTasks[$i]['start_date']);
				$newTaskTime = new \DateTime($newTask['start_date']);

				$currentTaskTime->modify('+8 hours');
				if($newTaskTime > $currentTaskTime) continue;
				else {
					return [
						'status' => 400,
						'message' => 'Bad request',
						'description' => 'New task should be at least 8 hours ahead'
					];
				}
			}
		}
		$dbConn = $this->dbController->openConnection();
		$sql = "INSERT INTO tasks (team_id, room_id, start_date, worker_id ,note, status, isActive) VALUE (:team_id, :room_id, :start_date, :worker_id ,:note, 0, 1)";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':team_id', $newTask['team_id']);
		$stmt->bindParam(':room_id', $newTask['room_id']);
		$stmt->bindParam(':start_date', $newTask['start_date']);
		$stmt->bindParam(':worker_id', $newTask['worker_id']);
		$stmt->bindParam(':note', $newTask['note']);
		$stmt->execute();

		//when adding task to specific room, set that room to be active
		return $this->roomsRepository->updateRoomStatus($newTask['room_id']);
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

	public function getTasksByRoom(int $room_id): ?array
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT * FROM tasks WHERE room_id = :room_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':room_id', $room_id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAllTasksByCompany(int $company_id): array
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT room_id FROM rooms WHERE company_id = :company_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':company_id', $company_id);
		$stmt->execute();
		$rooms = $stmt->fetchAll(PDO::FETCH_FUNC, function ($room) {
			return $room;
		});
		$stmt->closeCursor();
		if(empty($rooms))
			return [];

		$tasks = "SELECT T.task_id, T.start_date, T.note, T.isActive,
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
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function insertTaskResponse(array $taskResponse): bool
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "UPDATE tasks SET summary = :summary, status = :status, end_date = :end_date, isActive = 0 WHERE task_id = :task_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':task_id', $taskResponse['task_id']);
		$stmt->bindParam(':summary', $taskResponse['task_summary']);
		$stmt->bindParam(':status', $taskResponse['status']);
		$end_date = date('Y-m-d H:i:s', time());
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
		$teams = $stmt->fetchAll(PDO::FETCH_FUNC, function ($team) {
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
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function generateArchiveRecord(int $task_id): array
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT I.item_name, 
                       CONCAT(W.worker_fname,' ',W.worker_lname) AS worker_full_name,
                       W.worker_id,
                       W.worker_email, 
                       W.phone_number,
                       SI.note,
                       SI.date_scanned,
                       P.picture_path AS additional_picture
			    FROM scanned_items SI
			    LEFT JOIN items I 
			    	   ON SI.item_id = I.item_id
			    LEFT JOIN pictures P
			    	   ON P.picture_id = SI.picture_id
			    LEFT JOIN workers W 
			    	   ON W.worker_id = SI.worker_id
			    WHERE task_id = :task_id";

		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':task_id', $task_id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function saveToArchive(array $archiveReports, int $task_id): bool
	{
		$dbConn = $this->dbController->openConnection();

		$flattenedArrayProps = array_merge(...array_map('array_values', $archiveReports));
		$columns = ['room_name', 'item_name', 'team_name', 'date_scanned', 'note', 'additional_picture', 'worker_id', 'worker_full_name', 'worker_email', 'worker_phone', 'archived_by', 'task_id'];
		$numOfCols = count($columns);
		$numOfRows = count($flattenedArrayProps) / $numOfCols;
		$row = '(' . implode(', ', array_fill(0, $numOfCols, '?')) . ')';
		$rows = implode(', ', array_fill(0, $numOfRows, $row));
		$sql = "INSERT INTO archive (room_name, item_name, team_name, date_scanned, note, additional_picture, worker_id, worker_full_name, worker_email, worker_phone, archived_by, task_id) VALUES $rows";
		$stmt = $dbConn->prepare($sql);
		$stmt->execute($flattenedArrayProps);

		$stmt->closeCursor();
		$sql = "UPDATE tasks SET isActive = 0 WHERE task_id = :task_id AND isActive != 0";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':task_id', $task_id);
		$stmt->execute();
		return $stmt->rowCount() > 0;
	}

	public function getArchivedTasksByUser(int $worker_id, string $role): array
	{
		$dbConn = $this->dbController->openConnection();
		switch ($role) {

			case 'worker':
				$sql = "SELECT A.room_name, A.item_name, A.team_name, A.date_scanned, A.note, A.additional_picture, A.worker_id, A.worker_full_name, A.worker_email, A.worker_phone,
       			        CONCAT(W.worker_fname,' ',W.worker_lname) AS archived_by, W.worker_email AS employer_email, W.phone_number AS employer_number
				FROM archive A 
         		LEFT JOIN workers W ON W.worker_id = A.archived_by
				WHERE A.worker_id = :worker_id";
				$stmt = $dbConn->prepare($sql);
				$stmt->bindParam(':worker_id', $worker_id);
				break;

			case 'employer':
				$sql = "SELECT *
				FROM archive
				WHERE archived_by = :worker_id";
				$stmt = $dbConn->prepare($sql);
				$stmt->bindParam(':worker_id', $worker_id);
				break;

			case 'admin':
				$sql = "SELECT * FROM archive";
				$stmt = $dbConn->prepare($sql);
				break;

			default:
				return [
					'status' => 401,
					'message' => 'Forbidden',
					'description' => 'You do not have permission to access this data'
				];
		}

		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}