<?php

namespace Repositories;

use Controllers\DatabaseController as DBController;
use PDO;

class CompaniesRepository
{
	private DBController $database;
	public function __construct(DBController $database)
	{
		$this->database = $database;
	}

	public function GetAllCompanies() {}
}