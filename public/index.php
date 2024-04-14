<?php

use Controllers\APIController as API;
use Middleware\JsonBodyParserMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use Dotenv\Dotenv as dotSetup;
use DI\Container;
use Tuupola\Middleware\CorsMiddleware as Cors;

require __DIR__ . '/../vendor/autoload.php';

#region cors
if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
		header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
		header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

}
#endregion

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

$app->run();
