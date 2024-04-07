<?php

use Controllers\APIController as API;
use Middleware\JsonBodyParserMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use Dotenv\Dotenv as dotSetup;
use DI\Container;
use Tuupola\Middleware\CorsMiddleware as Cors;

require __DIR__ . '/../vendor/autoload.php';

#region loadenv
$dEnv = dotSetup::createImmutable(__DIR__ . '/../');
$dEnv->safeLoad();
#endregion

#region di-container
$container = new Container();
AppFactory::setContainer($container);
#endregion

$app = AppFactory::create();

#region dependencies
$routes = require '../app/routes.php';
$routes($app);
#endregion

#region bootstrap
$app->add(new JsonBodyParserMiddleware());
$app->addBodyParsingMiddleware();
$app->add(new BasePathMiddleware($app));
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
#endregion

#region cors
$app->add(new Cors([
	"origin" => [$_ENV['MAIN_URL_FE']],
	"methods" => ["GET", "POST", "PUT", "PATCH", "DELETE", "OPTIONS"],
	"headers.allow" => ["Authorization"],
	"headers.expose" => [],
	"origin.server" => $_ENV['MAIN_URL_BE'],
	"credentials" => true,
	"cache" => 86400,
	"error" => function ($request, $response, $arguments) {
		$data["status"] = "error";
		$data["message"] = $arguments["message"];
		$response->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
		return $response
			->withHeader("Content-Type", "application/json");
	}
]));
#endregion

$app->run();
