<?php

use Models\ExceptionModel as ExceptionResponse;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @throws ErrorException
 */
function defaultErrorHandler($severity, $message, $file, $line): false
{
	if (!(error_reporting() & $severity)) return false;
	throw new ErrorException($message, 0, $severity, $file, $line);
}

function defaultErrorMiddleware(Request $request, Throwable $exception, $app) {

	$response = $app->getResponseFactory()->createResponse(500, "Internal server error");

	if($_ENV['IS_DEV']) {
		$errorObj = new Exception();
		$errorObj->setExceptionType((string)get_class($exception));
		$errorObj->setExceptionMessage((string)$exception->getMessage());
		$errorObj->setInfile((string)$exception->getFile());
		$errorObj->setAtLine((string)$exception->getLine());
		$errorObj->setExceptionCode((int)$exception->getCode());

		$response->getBody()->write(json_encode($errorObj->jsonSerialize()));
	}
	else {
		$errorObj = [
			'status' => 500,
			'message' => 'Internal server error',
			'description' => 'Error occurred, please try again or contact the administrator.',
			'errorDetails' => [
				'error-message' => $exception->getMessage(),
				'error-code' => $exception->getCode(),
			]
		];

		$response->getBody()->write(json_encode(json_encode($errorObj)));
	}

	return $response
		->withHeader('Content-Type', 'application/json')
		->withHeader('Access-Control-Allow-Origin', "{$_ENV['MAIN_URL_FE']}")
		->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
		->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
}
