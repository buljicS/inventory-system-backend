<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Generator as Generator;
use Middleware\JsonBodyParserMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use Dotenv\Dotenv as dotSetup;
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

$app->get('/api', function (Request $request, Response $response) {
	$openapi = Generator::scan(['../src/']);
	$jsonDoc = fopen("./swaggerui/swagger-docs.json", "w");
	fwrite($jsonDoc, $openapi->toJson());
	fclose($jsonDoc);
	$response->getBody()->write($openapi->toJson());
	if($_ENV['IS_DEV']) {
		return $response
			->withHeader('Location', './swaggerui')
			->withStatus(302);
	}
});

$app->post('/api/Users/LoginUser', [Controllers\APIController::class, 'LoginUser']);

$app->run();
