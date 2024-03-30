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
$app->options('/{routes:.+}', function ($request, $response, $args) {
	return $response;
});

$app->add(function ($request, $handler) {
	$response = $handler->handle($request);
	return $response
		->withHeader('Access-Control-Allow-Origin', '*')
		->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept')
		->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

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

$app->get("/getDoc", function (Psr\Http\Message\ServerRequestInterface $request, Psr\Http\Message\ResponseInterface $response) {
	$openapi = Generator::scan(['../src']);
	$openapiJSON = $openapi->toJson();
	$file = fopen("./swagger/openapi.json", "wa+");
	fwrite($file, $openapiJSON);
	fclose($file);
	$response->getBody()->write(file_get_contents("./swagger/openapi.json"));
	return $response
		->withHeader('Content-type', 'application/json');
});

$app->post('/api/Users/LoginUser', [Controllers\APIController::class, 'LoginUser']);

$app->post('/api/Users/RegisterUser', [\Controllers\APIController::class, 'RegisterUser']);

$app->run();
