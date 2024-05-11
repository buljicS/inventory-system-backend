<?php

declare(strict_types=1);

use Selective\BasePath\BasePathMiddleware;
use Slim\App as Slim;

return function (Slim $app) {
	$app->addBodyParsingMiddleware();
	$app->add(new BasePathMiddleware($app));
	$app->addRoutingMiddleware();
};