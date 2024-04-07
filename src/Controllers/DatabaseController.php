<?php

declare(strict_types=1);

namespace Controllers;

use Exception;
use PDO;

class DatabaseController
{
	public function __constructor() {}

	/**
	 * Connects to programatori database
	 * @return PDO object if connection is made
	 * @throws Exception with message if connection is not established
	 */
	public function OpenConnection(): PDO
    {
        $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}";

        try {
            return new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}
