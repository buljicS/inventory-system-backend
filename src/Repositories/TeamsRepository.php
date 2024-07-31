<?php

namespace Repositories;

use Controllers\DatabaseController as DBController;
use PDO;

class TeamsRepository
{
	private readonly DBController $dbController;
	public function __construct(DBController $dbController)
	{
		$this->dbController = $dbController;
	}

	public function getAllTeams(int $company_id): array
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT * FROM teams WHERE company_id = :company_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':company_id', $company_id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getTeamMembers(int $team_id): array
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT TM.team_member_id, TM.date_added, TM.isActive, 
       				   T.team_name,
       				   W.worker_id, W.worker_fname, W.worker_lname, W.worker_email, W.phone_number
				FROM team_members TM
         		LEFT JOIN teams T 
         		    ON T.team_id = TM.team_id
                LEFT JOIN workers W 
                    ON W.worker_id = TM.worker_id
         		WHERE TM.team_id = :team_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':team_id', $team_id);
		$stmt->execute();
		$teamMembers =$stmt->fetchAll(PDO::FETCH_ASSOC);

		$stmt->closeCursor();
		$picPath = "SELECT P.picture_path FROM workers W
					LEFT JOIN pictures P 
					    ON W.picture_id = P.picture_id
					WHERE W.worker_id = :worker_id";
		$stmt = $dbConn->prepare($picPath);
		for($i = 0; $i < count($teamMembers); $i++)
		{
			$stmt->bindParam(':worker_id', $teamMembers[$i]['worker_id']);
			$stmt->execute();
			$picture_path = $stmt->fetchColumn();
			$teamMembers[$i]['picture_path'] = !empty($picture_path) ? $picture_path : null;
		}
		return $teamMembers;
	}

	public function getActiveWorkers(int $company_id): array
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT W.worker_id, W.worker_fname, W.worker_lname, W.phone_number, W.worker_email, P.picture_path 
				FROM workers W 
				LEFT JOIN pictures P 
				    ON W.picture_id = P.picture_id
				WHERE company_id = :company_id AND isActive = 1 AND W.role = 'worker'";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':company_id', $company_id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function createNewTeam(array $newTeam): array
	{
		$notes = [];
		$dbConn = $this->dbController->openConnection();
		$sql = "INSERT INTO teams (team_name, company_id) VALUES (:team_name, :company_id)";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':team_name', $newTeam['team_name']);
		$stmt->bindParam(':company_id', $newTeam['company_id']);
		if($this->checkIfSameTeamAlreadyExists($newTeam['team_name']) === false) {
			$stmt->execute();
			$stmt->closeCursor();
			$team_id = $this->getTeamIdByName($newTeam['team_name']);
			for($i = 0; $i < count($newTeam['workers_ids']); $i++)
			{
				if($this->checkIfWorkerIsAlreadyInTeam($newTeam['workers_ids'][$i], $team_id)) {
					$notes[] = "Team member with id" . $newTeam['workers_ids'][$i] . " is already in this team.";
				}
				else {
					$query = "INSERT INTO team_members (team_id, worker_id) VALUES (:team_id, :worker_id)";
					$stmt = $dbConn->prepare($query);
					$stmt->bindParam(':team_id', $team_id);
					$stmt->bindParam(':worker_id', $newTeam['workers_ids'][$i]);
					$stmt->execute();
				}
			}

			$retVal = [
				'status' => 202,
				'message' => 'Created',
				'description' => 'New team created successfully'
			];

			if(count($notes) > 0)
				$retVal['notes'] = $notes;

			return $retVal;
		}

		return [
			'status' => 403,
			'message' => 'Forbidden',
			'description' => 'Team with ' . $newTeam['team_name'] . ' name already exists'
		];
	}

	public function addNewTeamMembers(array $teamMembers, int $team_id): array
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "INSERT INTO team_members (team_id, worker_id) VALUES (:team_id, :worker_id)";
		$stmt = $dbConn->prepare($sql);
		for($i = 0; $i < count($teamMembers); $i++)
		{
			$stmt->bindParam(':team_id', $team_id, PDO::PARAM_INT);
			$stmt->bindParam(':worker_id', $teamMembers[$i], PDO::PARAM_INT);
			$stmt->execute();
		}

		return [
			'status' => 202,
			'message' => 'Created',
			'description' => 'New team members added successfully'
		];
	}

//	public function removeMemberFromTeam(int $team_id, int $team_member_id)
//	{
//		$dbConn = $this->
//	}

	#region HelperMethods
	public function checkIfSameTeamAlreadyExists(string $team_name): bool|int
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT team_id FROM teams WHERE team_name = :team_name";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':team_name', $team_name);
		$stmt->execute();
		return $stmt->fetchColumn();
	}

	public function checkIfWorkerIsAlreadyInTeam(int $worker_id, int $team_id): bool|int
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT worker_id FROM team_members WHERE team_id = :team_id AND worker_id = :worker_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':team_id', $team_id);
		$stmt->bindParam(':worker_id', $worker_id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}

	public function getTeamIdByName(string $team_name): int
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT team_id FROM teams WHERE team_name = :team_name";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':team_name', $team_name);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	#endregion

}