<?php

namespace Repositories;

use PDO;
use Controllers\DatabaseController as DBController;


class QRCodesRepository
{
	private readonly DBController $dbController;

	public function __construct(DBController $dbController)
	{
		$this->dbController = $dbController;
	}

	public function insertNewQRCodes(array $qrcodes): bool
	{
		$dbConn = $this->dbController->openConnection();

		//prepare qrcodes for bulk insert
		$flattenedQRCodesProps = array_merge(...array_map('array_values', $qrcodes));

		$columns = ['file-name', 'title', 'item_id', 'room_id'];
		$numOfCols = count($columns);

		//calculate number of needed row and create placeholders for as much
		$numOfRows = count($flattenedQRCodesProps) / $numOfCols;
		$row = '(' . implode(', ', array_fill(0, $numOfCols, '?')) . ')';
		$rows = implode(', ', array_fill(0, $numOfRows, $row));

		//insert all data into qr codes table
		$sql = "INSERT INTO qr_codes (file_name, title, item_id, room_id) VALUES $rows";
		$stmt = $dbConn->prepare($sql);
		return $stmt->execute($flattenedQRCodesProps);

	}
}