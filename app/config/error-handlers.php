<?php

use Models\ExceptionResponse as ExceptionResponse;

/**
 * @throws ErrorException
 */
function defaultErrorHandler($severity, $message, $file, $line): false
{
	if (!(error_reporting() & $severity)) return false;
	throw new ErrorException($message, 0, $severity, $file, $line);
}

function defaultErrorMiddleware(Throwable $exception, $app) {

//	if ($exception instanceof PDOException)
//		$errorObj->setExceptionType("PDO Error");
//
//	elseif ($exception instanceof \PHPMailer\PHPMailer\Exception)
//		$errorObj->setExceptionType("PHPMailer Error");
//
//	elseif ($exception instanceof Exception)
//		$errorObj->setExceptionType("PHP-Runtime Error");
//
//	else
//		$errorObj->setExceptionType("Uncaught Error");

	$errorObj = new ExceptionResponse();
	$errorObj->setExceptionType(get_class($exception));
	$errorObj->setExceptionMessage($exception->getMessage());
	$errorObj->setInfile($exception->getFile());
	$errorObj->setAtLine($exception->getLine());
	$errorObj->setExceptionCode($exception->getCode());

	$response = $app->getResponseFactory()->createResponse(500, "Internal server error");
	$response->getBody()->write(json_encode($errorObj->jsonSerialize()));

	return $response
		->withHeader('Content-Type', 'application/json')
		->withHeader('Access-Control-Allow-Origin', "{$_ENV['MAIN_URL_FE']}")
		->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
		->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
}
