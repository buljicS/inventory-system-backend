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

$app->get('/users', function (Request $request, Response $response) {
	$con = DBController::getConnection();

	$sql = "SELECT * FROM users";
	$con->prepare($sql);
	$stmt = $con->prepare($sql);
})->setName('root');

$app->run();
