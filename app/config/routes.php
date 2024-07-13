<?php

declare(strict_types=1);

use Slim\App as Slim;
use Controllers\WebAPIController as API;


return function (Slim $app) {

	#region Main
	$app->get('/', [API::class, 'index']);
	$app->get("/getDoc", [API::class, 'generateDocs']);
	#endregion

	#region Users
	$app->post('/api/Users/loginUser', [API::class, 'loginUser']);
	$app->post('/api/Users/registerUser', [API::class, 'registerUser']);
	$app->post('/api/Users/sendPasswordResetEmail' , [API::class, 'sendPasswordResetMail']);
	$app->post('/api/Users/resetPassword' , [API::class, 'resetPassword']);
	$app->get('/api/Users/activateUserAccount/{token}', [API::class, 'activateUserAccount']);
	$app->post('/api/Users/setNewPassword', [API::class, 'setNewPassword']);
	$app->post('/api/Users/updateUser', [API::class, 'updateUserData']);
	$app->get('/api/Users/getUserInfo/{worker_id}', [API::class, 'getUserInfo']);
	$app->get('/api/Users/getAllUsers', [API::class, 'getAllUsers']);
	$app->post("/api/Users/createUser", [API::class, 'createUser']);
	$app->put("/api/Users/changeTempPassword", [API::class, 'changeTempPassword']);
	$app->delete("/api/Users/banUser/{worker_id}", [API::class, 'banUser']);
	$app->put("/api/Users/revokeUserAccess/{worker_id}", [API::class, 'revokeUserAccess']);
	$app->put("/api/Users/updateUserByAdmin", [API::class, 'updateUserByAdmin']);
	#endregion

	#region Admins
	$app->post('/api/Admins/loginAdmin', [API::class, 'loginAdmin']);

	#endregions

	#region Logs
	$app->post('/api/Logs/logAccess', [API::class, 'LogAccess']);
	$app->get('/api/Logs/getAllLogs', [API::class, 'getAllLogs']);
	#endregion

	#region FirebaseBucket
	$app->get('/api/FirebaseStorage/getAllFilesFromDir/{dir}', [API::class, 'getAllFiles']);
	#endregion

	#region Companies
	$app->get('/api/Companies/getAllCompanies', [API::class, 'getAllCompanies']);
	$app->get('/api/Companies/getCompanyById/{company_id}', [API::class, 'getCompanyById']);
	$app->post('/api/Companies/addCompany', [API::class, 'addCompany']);
	$app->put('/api/Companies/updateCompany', [API::class, 'updateCompany']);
	$app->delete('/api/Companies/deleteCompany/{company_id}', [API::class, 'deleteCompany']);
	$app->put('/api/Companies/restoreCompany/{company_id}', [API::class, 'restoreCompany']);
	#endregion

	#region Rooms
	$app->post("/api/Rooms/addRoom", [API::class, 'addRoom']);
	$app->get("/api/Rooms/getAllRooms", [API::class, 'getAllRooms']);
	$app->get("/api/Rooms/getAllRoomsByCompanyId/{company_id}", [API::class, 'getAllRoomsByCompanyId']);
	$app->delete("/api/Rooms/deleteRoom/{room_id}", [API::class, 'deleteRoom']);
	#endregion

	#region TestEndpoints
	$app->post('/api/Test/listTest', [API::class, 'listTest']);
	#endregion

};
