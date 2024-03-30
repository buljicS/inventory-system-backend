<?php

use Middleware\JsonBodyParserMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use Dotenv\Dotenv as dotSetup;
use OpenApi\Generator as Generator;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

//load env file
$dEnv = dotSetup::createImmutable(__DIR__ . '/../');
$dEnv->safeLoad();

//php dep injection container
$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

//json parser middleware
$app->add(new JsonBodyParserMiddleware());

//cors policy
if (isset($_SERVER['HTTP_ORIGIN'])) {
	// should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
	// whitelist of safe domains
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
//body parsing middleware
$app->addBodyParsingMiddleware();

//routing middleware
$app->addRoutingMiddleware();

//base-path middleware
$app->add(new BasePathMiddleware($app));

//error middleware
$app->addErrorMiddleware(true, true, true);

//routes
$app->get('/', [Controllers\APIController::class, 'Index']);

$app->get("/getDoc", [Controllers\APIController::class, 'GenerateDocs']);

$app->post('/api/Users/LoginUser', [Controllers\APIController::class, 'LoginUser']);

$app->post('/api/Users/RegisterUser', [Controllers\APIController::class, 'RegisterUser']);

$app->post('/api/Users/SendPasswordResetEmail' , [Controllers\APIController::class, 'SendPasswordResetMail']);

$app->run();
