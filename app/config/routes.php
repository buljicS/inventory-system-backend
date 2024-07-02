<?php

declare(strict_types=1);

use Slim\App as Slim;
use Controllers\WebAPIController as API;


return function (Slim $app) {

	#region Main
	$app->get('/', [API::class, 'Index']);
	$app->get("/getDoc", [API::class, 'generateDocs']);
	#endregion

	#region Users
	$app->post('/api/Users/LoginUser', [API::class, 'loginUser']);
	$app->post('/api/Users/RegisterUser', [API::class, 'registerUser']);
	$app->post('/api/Users/SendPasswordResetEmail' , [API::class, 'sendPasswordResetMail']);
	$app->post('/api/Users/ResetPassword' , [API::class, 'resetPassword']);
	$app->get('/api/Users/ActivateUserAccount/{token}', [API::class, 'activateUserAccount']);
	$app->post('/api/Users/SetNewPassword', [API::class, 'setNewPassword']);
	$app->post('/api/Users/UpdateUser', [API::class, 'updateUserData']);
	$app->get('/api/Users/GetUserInfo/{worker_id}', [API::class, 'getUserInfo']);
	#endregion

	#region Admins
	$app->post('/api/Admins/LoginAdmin', [API::class, 'loginAdmin']);
	$app->get('/api/Admins/GetAllUsers', [API::class, 'getAllUsers']);
	#endregions

	#region Logs
	$app->post('/api/Logs/LogAccess', [API::class, 'LogAccess']);
	$app->get('/api/Logs/GetAllLogs', [API::class, 'getAllLogs']);
	#endregion

	#region FirebaseBucket
	$app->get('/api/FirebaseStorage/GetAllFilesFromDir/{dir}', [API::class, 'getAllFiles']);
	#endregion

	#region Companies
	$app->get('/api/Companies/GetAllCompanies', [API::class, 'getAllCompanies']);
	$app->post('/api/Companies/UpdateCompany', [API::class, 'updateCompany']);
	#endregion

};
