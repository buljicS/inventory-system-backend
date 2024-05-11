<?php

use Slim\Factory\AppFactory;
use Dotenv\Dotenv as dotSetup;
use DI\Container;
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

//base middlewares
$base = require '../app/base.php';
$base($app);

//cors policy
$cors = require '../app/cors.php';
$cors($app);

//jwt auth
$jwtAuth = require '../app/jwt-auth.php';
$jwtAuth($app);

//error handling
$app->addErrorMiddleware(true, true, true);

//routes
$routes = require '../app/routes.php';
$routes($app);
#endregion

$app->run();
