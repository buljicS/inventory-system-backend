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

	public function insertNewItems(array $items, int $numberOfItems): array
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
			$sql = "SELECT item_id, room_id, item_name FROM items WHERE item_id = $last_id";
		else
			$sql = "SELECT item_id, room_id, item_name FROM items WHERE item_id BETWEEN $first_id AND $last_id";
		$stmt = $dbConn->prepare($sql);
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
		$stmt->execute();
		return $stmt->rowCount() > 0;
	}

	public function checkIfItemIsActive(int $item_id): bool
	{
		$dbConn = $this->dbController->openConnection();
		$takeRoomIdQuery = $dbConn->prepare("SELECT room_id FROM items WHERE item_id = :item_id");
		$takeRoomIdQuery->bindParam(':item_id', $item_id, PDO::PARAM_INT);
		$takeRoomIdQuery->execute();
		$roomId = $takeRoomIdQuery->fetch()['room_id'];

		$isOnActiveInventoryQuery = $dbConn->prepare("SELECT task_id FROM tasks WHERE room_id = $roomId AND isActive = 1");
		$isOnActiveInventoryQuery->execute();
		$isOnActiveInventory = $isOnActiveInventoryQuery->fetch();
		if(!empty($isOnActiveInventory))
			return true;

		return false;
	}

//	public function deleteItem(int $item_id): bool
//	{
//		$dbConn = $this->dbController->openConnection();
//
//		//delete QR code for item that is being deleted
//		$deleteCorrespondingQRCode = "DELETE FROM qr_codes WHERE item_id = :item_id";
//		$stmt = $dbConn->prepare($deleteCorrespondingQRCode);
//		$stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
//		$stmt->execute();
//		$stmt = null;
//
//		//delete item
//		$deleteItem = "DELETE FROM items WHERE item_id = :item_id";
//		$stmt = $dbConn->prepare($deleteItem);
//		$stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
//		return $stmt->execute();
//
//	}

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
}