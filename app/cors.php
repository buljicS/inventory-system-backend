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
		"credentials" => false,
		"cache" => 0
	]));
};