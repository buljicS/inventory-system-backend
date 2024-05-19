<?php

declare(strict_types=1);

use Slim\App as Slim;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (Slim $app) {
	require_once __DIR__ . '/../config/error-handlers.php';

	$errorMiddleware = $app->addErrorMiddleware(true, true, true);

	set_error_handler('defaultErrorHandler');

	$errorMiddleware->setDefaultErrorHandler(function (Request $request, Throwable $exception) use ($app) {
		return defaultErrorMiddleware($exception, $app);
	});
};

