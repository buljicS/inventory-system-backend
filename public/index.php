<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use Services\UserServices as User;
use Dotenv\Dotenv as dotSetup;
use DI\Container;
use OpenApi\Generator as OG;
use Services\UserServices as US;

require __DIR__ . '/../vendor/autoload.php';

//load env file
$dEnv = dotSetup::createImmutable(__DIR__ . '/../');
$dEnv->safeLoad();

//php dep injection container
$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

//body parsing middleware
$app->addBodyParsingMiddleware();

//routing middleware
$app->addRoutingMiddleware();

//base-path middleware
$app->add(new BasePathMiddleware($app));

//error middleware
$app->addErrorMiddleware(true, true, true);

//$app->get('/', function (Request $request, Response $response) {
//    $response->getBody()->write('Hello, World!');
//    return $response;
//})->setName('root');

$app->get('/', function (Request $request, Response $response) {
	$openapi = OG::scan(['../src/']);
	$jsonDoc = fopen("./swagger/swagger-docs.json", "w");
	fwrite($jsonDoc, $openapi->toJson());
	fclose($jsonDoc);
	$response->getBody()->write($openapi->toJson());
	if($_ENV['IS_DEV']) {
		return $response
			->withHeader('Location', './swagger')
			->withStatus(302);
	}
	return $response
		->withHeader('Location', '.')
		->withStatus(401);
});

$app->post('/api/loginUser', function (Request $request, Response $response) {
	$body = $request->getParsedBody();
	$email = strip_tags(trim($body['email']));
	$password = strip_tags(trim($body['password']));
	$authUser = new US();
	$resp = $authUser->authenticateUserService($email, $password);

	$response->getBody()->write(json_encode($resp));
	return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/getAllUsers', function (Request $request, Response $response) {
	$usr = new User();
	$data = $usr->getAllUsersService();
	$response->getBody()->write(json_encode($data));
	return $response->withHeader('Content-Type', 'application/json');
})->setName('root');

$app->run();
