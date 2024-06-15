<?php

declare(strict_types=1);

use Slim\App as Slim;
use Controllers\WebAPIController as API;


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
	$app->post('/api/Users/SetNewPassword', [API::class, 'SetNewPassword']);
	$app->post('/api/Users/UpdateUser', [API::class, 'UpdateUserData']);
	$app->get('/api/Users/GetUserInfo/{worker_id}', [API::class, 'GetUserInfo']);
	#endregion

	#region Admins
	$app->post('/api/Admins/LoginAdmin', [API::class, 'LoginAdmin']);
	$app->get('/api/Admins/GetAllCompanies', [API::class, 'GetAllCompanies']);
	#endregions

	#region Logs
	$app->post('/api/Logs/LogAccess', [API::class, 'LogAccess']);
	$app->get('/api/Logs/GetAllLogs', [API::class, 'GetAllLogs']);
	#endregion

	#region FirebaseBucket
	$app->get('/api/FirebaseStorage/GetAllFilesFromDir/{dir}', [API::class, 'GetAllFiles']);
	#endregion

};