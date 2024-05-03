<?php

declare(strict_types=1);

use Slim\App as Slim;
use Controllers\APIController as API;


return function (Slim $app) {

	#region Main
	$app->get('/', [API::class, 'Index']);
	$app->get("/getDoc", [API::class, 'GenerateDocs']);
	#endregion

	#region Users
	$app->post('/api/Users/LoginUser', [API::class, 'LoginUser']);
	$app->post('/api/Users/RegisterUser', [API::class, 'RegisterUser']);
	$app->post('/api/Users/SendPasswordResetEmail' , [API::class, 'SendPasswordResetMail']);
	$app->post('/api/Users/ResetPassword' , [API::class, 'ResetPassword']);
	$app->get('/api/Users/ActivateUserAccount/{token}', [API::class, 'ActivateUserAccount']);
	#endregion

	#region Logs
	$app->get('/api/Users/LogAccess', [API::class, 'LogAccess']);
	$app->get('/api/Users/GetAllLogs', [API::class, 'GetAllLogs']);
	#endregion

};
