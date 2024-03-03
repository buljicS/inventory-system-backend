<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use App\DatabaseController as myDBC;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// Parse json, form data and xml
$app->addBodyParsingMiddleware();

// Add Slim routing middleware
$app->addRoutingMiddleware();

$app->add(new BasePathMiddleware($app));

$app->addErrorMiddleware(true, true, true);

// Define app routes
$app->get('/scores', function (Request $request, Response $response) {
	$pdo = myDBC::getConnection();
	$stmt = $pdo->query('SELECT * FROM scores');
	$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$payload = json_encode($data, JSON_PRETTY_PRINT);
	$response->getBody()->write($payload);
	return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(200, "OK");
})->setName('root');

$app->get('/scores/{id}', function (Request $request, Response $response, array $args) {
	$pdo = myDBC::getConnection();
	$stmt = $pdo->query('SELECT * FROM scores WHERE score_id=' . $args['id']);
	$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$payload = json_encode($data, JSON_PRETTY_PRINT);
	$response->getBody()->write($payload);
	return $response
		->withHeader('Content-Type', 'application/json')
		->withStatus(200, "OK");
})->setName('root');

// Run app
$app->run();
