<?php

use Selective\BasePath\BasePathMiddleware;
use Tuupola\Middleware\CorsMiddleware as CORSMiddleware;
use Slim\Factory\AppFactory;
use Dotenv\Dotenv as dotSetup;
use DI\Container;

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

#region bootstrap
$app->addBodyParsingMiddleware();
$app->add(new BasePathMiddleware($app));
$app->addRoutingMiddleware();
$app->add(new CORSMiddleware ([
	"origin" => ["http://localhost:3000"],
	"methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
	"headers.allow" => ["Origin", "Authorization", "X-Requested-With", "Content-Type", "Accept"],
	"origin.server" => "http://www.insystem-api.localhost/",
	"headers.expose" => [],
	"credentials" => false,
	"cache" => 0,
	"error" => function ($request, $response, $arguments) {
		$data["status"] = "error";
		$data["message"] = $arguments["message"];
		return $response
			->withHeader("Content-Type", "application/json")
			->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
	}
]));
$app->addErrorMiddleware(true, true, true);
#endregion

#region dependencies
$routes = require '../app/routes.php';
$routes($app);
#endregion

$app->run();
