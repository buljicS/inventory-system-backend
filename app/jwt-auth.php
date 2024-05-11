<?php

declare(strict_types=1);

use Slim\App as Slim;
use Tuupola\Middleware\JwtAuthentication as Auth;


return function (Slim $app) {
	require_once 'config.php';

	$app->add(new Auth([
		"path" => $authPath,
		"ignore" => $ignorePath,
		"secure" => false,
		"secret" => $_ENV["JWT_SECRET"],
		"algorithm" => "HS256",
		"error" => function ($response, $arguments) {
			$data["status"] = "Authorization Error";
			$data["message"] = $arguments["message"];

			$response->getBody()->write(
				json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
			);

			return $response
				->withHeader("Content-Type", "application/json");
		}
	]));
};