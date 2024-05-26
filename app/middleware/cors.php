<?php

declare(strict_types=1);

use Tuupola\Middleware\CorsMiddleware as CORSMiddleware;
use Slim\App as Slim;

return function (Slim $app) {
	$app->add(new CORSMiddleware([
		"origin" => ["{$_ENV['MAIN_URL_FE']}"],
		"methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
		"headers.allow" => ["Origin", "Authorization", "X-Requested-With", "Content-Type", "Accept"],
		"origin.server" => "{$_ENV['MAIN_URL_BE']}",
		"headers.expose" => [],
		"credentials" => true,
		"cache" => 86400,
		"error" => function ($request, $response, $arguments) {

			$data = [
				"status" => "CORS Error",
				"message" => $arguments["message"]
			];

			$response
				->getBody()
				->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

			return $response
				->withHeader("Content-Type", "application/json");
		}
	]));
};