<?php

namespace Providers;

use Controllers\DatabaseTestController;
use Interfaces\ServiceProviderInterface;
use PDO;
use PDOException;
use Psr\Container\ContainerInterface;

class PDOServiceProvider implements ServiceProviderInterface
{
	public static function register(ContainerInterface $container): void {

		$container->set(DatabaseTestController::class, function(ContainerInterface $container) {
			require_once __DIR__ . '/../../app/config/config.php';

			$dsn = "{$_ENV['DB_DRIVER']}:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}";
			try {
				$pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $pdoOptions);
				return new DatabaseTestController($pdo);
			}
			catch (PDOException $e) {
				throw new PDOException($e->getMessage());
			}
		});

	}

	public static function boot() { /* Use after registration */ }
}