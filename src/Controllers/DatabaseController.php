<?php

declare(strict_types=1);

namespace Controllers;

use PDO;
use Dotenv\Dotenv as dotSetup;
use Exception;

class DatabaseController
{
	/**
	 * Function connects to the database
	 * @return PDO object if connection is done
	 * @throws Exception if there is an error
	 */
	public static function getConnection(): PDO
    {
		$dEnv = dotSetup::createImmutable(__DIR__ . '/../../.env');
		$dEnv->safeLoad();

        $dsn = "mysql:host={$dEnv['DB_HOST']};dbname={$dEnv['DB_NAME']};charset={$dEnv['DB_CHARSET']}";

        try {
            return new PDO($dsn, $dEnv['DB_USER'], $dEnv['DB_PASSWORD'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}
