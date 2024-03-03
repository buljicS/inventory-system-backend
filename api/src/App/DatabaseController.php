<?php

declare(strict_types=1);

namespace App;
use PDO;

class DatabaseController
{
	public static function getConnection():PDO
	{
		$dsn = "mysql:host=localhost;dbname=highscores;charset=utf8";

		return new PDO($dsn, 'root', '', [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		]);
	}
}