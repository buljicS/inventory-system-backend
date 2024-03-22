<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use Controllers\DatabaseController as DBController;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

//body parsing middleware
$app->addBodyParsingMiddleware();

//routing middleware
$app->addRoutingMiddleware();

//base-path middleware
$app->add(new BasePathMiddleware($app));

//error middleware
$app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Hello, World!');
    return $response;
})->setName('root');

$app->get('/getAllUsers', function (Request $request, Response $response) {
	$con = DBController::openConnection();

	$stmt = $con->prepare("SELECT * FROM workers");
	$stmt->execute();
	$data = $stmt->fetchAll();
	$response->getBody()->write(json_encode($data));
	return $response->withHeader('Content-Type', 'application/json');
})->setName('root');

$app->run();
