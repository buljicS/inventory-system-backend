<?php

use Slim\Factory\AppFactory;
use Dotenv\Dotenv as dotSetup;
use DI\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Tuupola\Middleware\JwtAuthentication as Auth;

require __DIR__ . '/../vendor/autoload.php';

#region loadenv
$dEnv = dotSetup::createImmutable(__DIR__ . '/../');
$dEnv->safeLoad();
#endregion

#region di-container
$container = new Container();
AppFactory::setContainer($container);
#endregion

#region dependencies
$app = AppFactory::create();

//config middlewares
$base = require '../app/middleware/base.php';
$base($app);

//cors policy
$cors = require '../app/middleware/cors.php';
$cors($app);

//jwt auth
$jwtAuth = require '../app/middleware/authorization.php';
$jwtAuth($app);

//error handling
$errorHandler = require '../app/middleware/error.php';
$errorHandler($app);

//routes
$routes = require '../app/config/routes.php';
$routes($app);
#endregion

$app->run();
