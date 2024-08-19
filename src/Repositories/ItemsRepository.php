<?php

namespace Repositories;

use Controllers\DatabaseController as DBController;
use PDO;

class ItemsRepository
{

	private readonly DBController $dbController;

	public function __construct(DBController $dbController)
	{
		$this->dbController = $dbController;
	}

	public function insertNewItems(array $items, int $numberOfItems, int $room_id): array
	{

		$dbConn = $this->dbController->openConnection();

		//prepare items for multiple insertion
		$flattenedItemProps = array_merge(...array_map('array_values', $items));

		//create rows for multi insertion
		$columns = ['item_name', 'serial_no', 'country_of_origin', 'room_id']; //entity columns
		$numOfCols = count($columns);

		$numOfRows = count($flattenedItemProps) / $numOfCols;

		$row = '(' . implode(', ', array_fill(0, $numOfCols, '?')) . ')'; //representation of single row ('?','?','?','?','?')
		$rows = implode(', ', array_fill(0, $numOfRows, $row));
		$sql = "INSERT INTO items (item_name, serial_no, country_of_origin, room_id) VALUES $rows";
		$stmt = $dbConn->prepare($sql);
		$stmt->execute($flattenedItemProps);

		$first_id = $dbConn->lastInsertId();
		$last_id = $first_id + ($numberOfItems - 1);

		//after inserting all items fetch their props in case of qr code generation
		$stmt->closeCursor();
		if($numberOfItems == 1)
			$sql = "SELECT item_id, room_id, item_name FROM items WHERE item_id = $last_id AND room_id = :room_id";
		else
			$sql = "SELECT item_id, room_id, item_name FROM items WHERE item_id BETWEEN $first_id AND $last_id AND room_id = :room_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(":room_id", $room_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);

		//REFERENCE https://stackoverflow.com/a/4559320
	}

	public function getItemsByRoom(int $room_id): ?array
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT I.item_id,
       				   I.item_name,
       				   I.serial_no,
       				   I.country_of_origin,
    				   P.picture_path, 
       				   P.picture_name
				FROM items I
         		LEFT JOIN pictures P 
         		    ON P.picture_id = I.picture_id		
         		WHERE room_id = :room_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':room_id', $room_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function updateItem(array $updatedItem): bool
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "UPDATE items SET item_name = :item_name, serial_no = :serial_no, country_of_origin = :country_of_origin WHERE item_id = :item_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':item_name', $updatedItem['item_name'], PDO::PARAM_STR);
		$stmt->bindParam(':serial_no', $updatedItem['serial_no'], PDO::PARAM_STR);
		$stmt->bindParam(':country_of_origin', $updatedItem['country_of_origin'], PDO::PARAM_STR);
		$stmt->bindParam(':item_id', $updatedItem['item_id'], PDO::PARAM_INT);
		return $stmt->execute();
	}

	public function checkIfItemIsActive(int $item_id): string
	{
		$dbConn = $this->dbController->openConnection();
		$takeRoomIdQuery = $dbConn->prepare("SELECT room_id FROM items WHERE item_id = :item_id");
		$takeRoomIdQuery->bindParam(':item_id', $item_id, PDO::PARAM_INT);
		$takeRoomIdQuery->execute();
		$roomId = $takeRoomIdQuery->fetchColumn();
		if($roomId === false) //if there is no room_id that means that item does not exist
			return "Item does not exists";

		else
			$isOnActiveInventoryQuery = $dbConn->prepare("SELECT task_id FROM tasks WHERE room_id = $roomId AND isActive = 1");
			$isOnActiveInventoryQuery->execute();
			$isActive = $isOnActiveInventoryQuery->fetchColumn();
			if($isActive !== false) return "Item is in active inventory task";

			return "ok";
	}

	public function deleteItem(int $item_id): string
	{
		$dbConn = $this->dbController->openConnection();

		//select picture_id
		$sql = "SELECT picture_id FROM items WHERE item_id = :item_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
		$stmt->execute();
		$picture_id = $stmt->fetchColumn();

		//delete item
		$stmt->closeCursor();
		$sql = "DELETE FROM items WHERE item_id = :item_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
		$stmt->execute();

		//check if item has picture
		if(!empty($picture_id)) {
			//select picture path
			$pictureQ = "SELECT picture_path FROM pictures WHERE picture_id = :picture_id";
			$stmt = $dbConn->prepare($pictureQ);
			$stmt->bindParam(':picture_id', $picture_id, PDO::PARAM_INT);
			$stmt->execute();
			$picturePath = $stmt->fetchColumn();

			//delete picture
			$stmt->closeCursor();
			$deletePictureQ = "DELETE FROM pictures WHERE picture_id = :picture_id";
			$stmt = $dbConn->prepare($deletePictureQ);
			$stmt->bindParam(':picture_id', $picture_id, PDO::PARAM_INT);
			$stmt->execute();

			return $picturePath;
		}
		return "ok";
	}

	public function setQRCodesOnItems(array $qrcodes): bool
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "UPDATE items SET picture_id = :picture_id WHERE item_id = :item_id";
		$stmt = $dbConn->prepare($sql);
		for($i = 0; $i < count($qrcodes); $i++) {
			$stmt->bindParam(':picture_id', $qrcodes[$i]['picture_id'], PDO::PARAM_INT);
			$stmt->bindParam(':item_id', $qrcodes[$i]['item_id'], PDO::PARAM_INT);
			$stmt->execute();
		}
		return $stmt->rowCount() == count($qrcodes);
	}

	public function canUserScan(int $worker_id, int $task_id): ?int
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT task_id FROM workers WHERE worker_id = :worker_id AND task_id = :task_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':worker_id', $worker_id, PDO::PARAM_INT);
		$stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchColumn();
	}

	public function isQRCodeAlreadyScanned(int $task_id, int $item_id): string
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "SELECT end_date FROM tasks WHERE task_id = :task_id";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
		$stmt->execute();
		$end_date = $stmt->fetchColumn();
		if($end_date == null) {
			$stmt->closeCursor();
			$scannedItem = "SELECT scanned_item_id FROM scanned_items WHERE task_id = :task_id AND item_id = :item_id";
			$stmt = $dbConn->prepare($scannedItem);
			$stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
			$stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
			$stmt->execute();
			$scannedItemId = $stmt->fetchColumn();
			if(!empty($scannedItemId))
				return "Already scanned";
			else
				return "OK";
		}
		return "Task ended";
	}

	public function insertScannedItem(array $scannedItem): bool
	{
		$dbConn = $this->dbController->openConnection();
		$sql = "INSERT INTO scanned_items (item_id, worker_id, task_id, note, state, picture_id) VALUE (:item_id, :worker_id, :task_id, :note, :state, :picture_id)";
		$stmt = $dbConn->prepare($sql);
		$stmt->bindParam(':item_id', $scannedItem['item_id'], PDO::PARAM_INT);
		$stmt->bindParam(':worker_id', $scannedItem['worker_id'], PDO::PARAM_INT);
		$stmt->bindParam(':task_id', $scannedItem['task_id'], PDO::PARAM_INT);
		$stmt->bindParam(':note', $scannedItem['note'], PDO::PARAM_STR);
		$stmt->bindParam(':state', $scannedItem['state'], PDO::PARAM_STR);
		$stmt->bindParam(':picture_id', $scannedItem['picture_id'], PDO::PARAM_INT);
		return $stmt->execute();
	}
}