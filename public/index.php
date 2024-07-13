<?php

date_default_timezone_set('Europe/Belgrade');

use Slim\Factory\AppFactory;
use Dotenv\Dotenv as dotSetup;
use DI\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/config/config.php';

#region loadenv
$dEnv = dotSetup::createImmutable(__DIR__ . '/../');
$dEnv->safeLoad();
#endregion

#region di-container
$container = new Container();
array_walk($serviceProviders, fn($sProvider) => $sProvider::register($container));
array_walk($serviceProviders, fn($sProvider) => $sProvider::boot());
AppFactory::setContainer($container);
#endregion

#region middlewares
$app = AppFactory::create();

//config middlewares
$base = require '../app/middleware/base.php';
$base($app);

//cors policy middleware
$cors = require '../app/middleware/cors.php';
$cors($app);

//rbac middleware
$rbac = require '../app/middleware/rbac.php';
$rbac($app);

//jwt auth middleware
$jwtAuth = require '../app/middleware/authorization.php';
$jwtAuth($app);

//routes
$routes = require '../app/config/routes.php';
$routes($app);

$app->addRoutingMiddleware();

//error handling middleware
$errorHandler = require '../app/middleware/error.php';
$errorHandler($app);
#endregion



$app->run();
