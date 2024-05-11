<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App as Slim;
use Models\ExceptionResponse as ErrorModel;

return function (Slim $app) {
	$errorMiddleware = $app->addErrorMiddleware(true, true, true);

	set_error_handler(function ($severity, $message, $file, $line) {
		if (!(error_reporting() & $severity)) return false;
		throw new ErrorException($message, 0, $severity, $file, $line);
	});

	$errorMiddleware->setDefaultErrorHandler(function (Request $request, Throwable $exception) use ($app) {

		$errorObj = new ErrorModel();
		if ($exception instanceof PDOException)
			$errorObj->setExceptionType("PDOException");

		elseif ($exception instanceof Exception) {
			$errorObj->setExceptionType("PHPRuntimeException");
		}

		$errorObj->setExceptionMessage((string)$exception->getMessage());
		$errorObj->setInfile($exception->getFile());
		$errorObj->setAtLine($exception->getLine());
		$errorObj->setExceptionCode(intval($exception->getCode()));

		$response = $app->getResponseFactory()->createResponse(500);
		$response->getBody()->write(json_encode($errorObj->jsonSerialize()));

		return $response
			->withHeader('Content-Type', 'application/json')
			->withHeader('Access-Control-Allow-Origin', "{$_ENV['MAIN_URL_FE']}")
			->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
			->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
	});
};

