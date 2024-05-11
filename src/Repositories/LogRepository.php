<?php

namespace Repositories;

use Controllers\DatabaseController as DBController;
use PDO;

class LogRepository
{
	private DBController $_database;
	public function __construct(DBController $database)
	{
		$this->_database = $database;
	}

	public function GetAllLogs(): ?array
	{
		$conn = $this->_database->OpenConnection();
		$sql = "SELECT * FROM access_logs";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function InsertNewLog(array $accessLog):void
	{
		$conn = $this->_database->OpenConnection();
		$sql = "INSERT INTO access_logs (user_agent, worker_id, referer, ip_address, device_type, is_logged_in)
					   VALUE (:user_agent, :worker_id, :referer, :ip_address, :device_type, :is_logged_in)";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':user_agent', $accessLog['user_agent']);
		$stmt->bindValue(':worker_id', $accessLog['worker_id']);
		$stmt->bindValue(':referer', $accessLog['referer']);
		$stmt->bindValue(':ip_address', $accessLog['ip_address']);
		$stmt->bindValue(':device_type', $accessLog['device_type']);
		$stmt->bindValue(':is_logged_in', $accessLog['is_logged_in']);
		$stmt->execute();
		$conn = null;
	}


}