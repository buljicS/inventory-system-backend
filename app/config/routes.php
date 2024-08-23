<?php

declare(strict_types=1);

use Slim\App as Slim;
use Controllers\WebAPIController as API;


return function (Slim $app) {

	#region Main
	$app->get('/', [API::class, 'index']);
	$app->get("/dashboard", [API::class, 'dashboard']);
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
	$app->post('/api/Users/uploadUserPicture/{worker_id}', [API::class, 'uploadUserPicture']);
	$app->delete('/api/Users/deleteUserPicture/{worker_id}/{userPicture}', [API::class, 'deleteUserPicture']);
	$app->get('/api/Users/enrollUserToTask/{worker_id}/{task_id}', [API::class, 'enrollUserToTask']);
	$app->get('/api/Users/removeUserFromTask/{worker_id}', [API::class, 'removeUserFromTask']);
	#endregion

	#region Admins
	$app->post('/api/Admins/loginAdmin', [API::class, 'loginAdmin']);
	#endregions

	#region Logs
	$app->post('/api/Logs/logAccess', [API::class, 'LogAccess']);
	$app->get('/api/Logs/getAllLogs', [API::class, 'getAllLogs']);
	#endregion

	#region FirebaseBucket
	$app->get('/api/FirebaseStorage/getFileByName/{dir}/{fileName}', [API::class, 'getFileByName']);
	$app->get('/api/FirebaseStorage/getAllFilesByDir/{dir}' , [API::class, 'getAllFilesByDir']);
	$app->post('/api/FirebaseStorage/uploadFile', [API::class, 'uploadFile']);
	$app->delete('/api/FirebaseStorage/deleteFile/{dir}/{fileName}', [API::class, 'deleteFile']);
	$app->get('/api/FirebaseStorage/downloadFile/{remoteDir}/{fileName}/{destinationDir}', [API::class, 'downloadFile']);
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
	$app->put("/api/Rooms/updateRoom", [API::class, 'updateRoom']);
	$app->get("/api/Rooms/checkRoomForActiveTasks/{room_id}", [API::class, 'checkRoomForActiveTasks']);
	#endregion

	#region Items
	$app->get('/api/Items/getItemsByRoom/{room_id}', [API::class, 'getItemsByRoom']);
	$app->post('/api/Items/createNewItems', [API::class, 'createNewItems']);
	$app->put('/api/Items/updateItem', [API::class, 'updateItem']);
	$app->delete('/api/Items/deleteItem/{item_id}', [API::class, 'deleteItem']);
	$app->post('/api/Items/scanItem', [API::class, 'scanItem']);
	#endregion

	#region QRCodes
	$app->post('/api/QRCodes/generateQRCodes', [API::class, 'generateQRCode']);
	$app->post('/api/QRCodes/checkScannedQRCode', [API::class, 'checkScannedQRCode']);
	#endregion

	#region Teams
	$app->get('/api/Teams/getActiveWorkersInCompany/{company_id}', [API::class, 'getActiveWorkersInCompany']);
	$app->get('/api/Teams/getAllTeamsInCompany/{company_id}', [API::class, 'getAllTeamsInCompany']);
	$app->get('/api/Teams/getTeamMembers/{team_id}', [API::class, 'getTeamMembers']);
	$app->post('/api/Teams/createNewTeam', [API::class, 'createNewTeam']);
	$app->post('/api/Teams/addTeamMembers', [API::class, 'addTeamMembers']);
	$app->delete('/api/Teams/removeTeamMemberFromTeam/{team_id}/{team_member_id}', [API::class, 'removeTeamMemberFromTeam']);
	$app->delete('/api/Teams/deleteTeam/{team_id}', [API::class, 'deleteTeam']);
	#endregion

	#region Tasks
	$app->post('/api/Tasks/addTask', [API::class, 'addTask']);
	$app->get('/api/Tasks/getAllTasksByCompany/{company_id}', [API::class, 'getAllTasksByCompany']);
	$app->get('/api/Tasks/taskCurrentStatus/{task_id}', [API::class, 'taskCurrentStatus']);
	$app->post('/api/Tasks/endTask', [API::class, 'endTask']);
	$app->get('/api/Tasks/getAllTasksForWorker/{worker_id}', [API::class, 'getAllTasksForWorker']);
	$app->post('/api/Tasks/archiveTask', [API::class, 'archiveTask']);
	$app->get('/api/Tasks/getArchivedTasksByUser/{worker_id}/{role}', [API::class, 'getArchivedTasksByUser']);
	#endregion
};
