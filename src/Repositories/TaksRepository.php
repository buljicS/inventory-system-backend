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
}