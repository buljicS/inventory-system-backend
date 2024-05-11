<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App as Slim;

return function (Slim $app) {
	$errorMiddleware = $app->addErrorMiddleware(true, true, true);
	$errorMiddleware->setDefaultErrorHandler(function (Request $request, Throwable $exception) use ($app) {
		$statusCode = $exception->getCode() ?: 500;
		$error = ['error' => $exception->getMessage()];
		$response = $app->getResponseFactory()->createResponse($statusCode);
		$response->getBody()->write(json_encode($error));
		return $response
			->withHeader('Content-Type', 'application/json')
			->withHeader('Access-Control-Allow-Origin', "{$_ENV['MAIN_URL_FE']}")
			->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
			->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
	});
};
