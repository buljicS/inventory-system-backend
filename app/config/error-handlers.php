<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Models\ExceptionResponse as ExceptionResponse;

/**
 * @throws ErrorException
 */
function defaultErrorHandler($severity, $message, $file, $line) {
	if (!(error_reporting() & $severity)) return false;
	throw new ErrorException($message, 0, $severity, $file, $line);
}

function defaultErrorMiddleware(Request $request, Throwable $exception, $app) {
	$errorObj = new ExceptionResponse();
	if ($exception instanceof PDOException)
		$errorObj->setExceptionType("PDOException");
	elseif ($exception instanceof Exception) {
		$errorObj->setExceptionType("PHPRuntimeException");
	}

	$errorObj->setExceptionMessage((string)$exception->getMessage());
	$errorObj->setInfile($exception->getFile());
	$errorObj->setAtLine($exception->getLine());
	$errorObj->setExceptionCode(intval($exception->getCode()));

	$response = $app->getResponseFactory()->createResponse(500, "Internal server error");
	$response->getBody()->write(json_encode($errorObj->jsonSerialize()));

	return $response
		->withHeader('Content-Type', 'application/json')
		->withHeader('Access-Control-Allow-Origin', "{$_ENV['MAIN_URL_FE']}")
		->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
		->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
}
