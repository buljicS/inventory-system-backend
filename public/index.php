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
	"origin" => ["{$_ENV['MAIN_URL_FE']}"],
	"methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
	"headers.allow" => ["Origin", "Authorization", "X-Requested-With", "Content-Type", "Accept"],
	"origin.server" => "{$_ENV['MAIN_URL_BE']}",
	"headers.expose" => [],
	"credentials" => false,
	"cache" => 0
]));
$app->addErrorMiddleware(true, true, true);
#endregion

#region dependencies
$routes = require '../app/routes.php';
$routes($app);
#endregion

$app->run();
