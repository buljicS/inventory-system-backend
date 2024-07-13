<?php

declare(strict_types=1);

use Slim\App as Slim;
use Tuupola\Middleware\JwtAuthentication as Auth;


return function (Slim $app) {
	require __DIR__ . '/../config/config.php';

	$app->add(new Auth([
		"ignore" => $ignorePath,
		"secure" => false,
		"secret" => $_ENV["JWT_SECRET"],
		"algorithm" => "HS256",
		"attribute" => "decoded-jwt",
		"error" => function ($response, $arguments) {
			$data["status"] = "Authorization Error";
			$data["message"] = $arguments["message"];

			$response->getBody()->write(
				json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
			);

			return $response
				->withHeader("Content-Type", "application/json")
				->withHeader('Access-Control-Allow-Origin', "{$_ENV['MAIN_URL_FE']}")
				->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
				->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
		}
	]));
};