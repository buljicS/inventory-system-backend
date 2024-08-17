<?php

 #region PDO Options
	$pdoOptions = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false
	];
 #endregion

 #region Authorization config
	$authPath = [
		"/api/Users/",
		"/api/Companies/",
		"/api/Rooms/",
		"/api/FirebaseStorage/",
		"/api/Logs/getAllLogs",
		"/api/Items/",
		"/api/QRCodes/",
		"/api/Teams",
		"/api/Tasks"
	];

	$ignorePath = [
		"/api/Users/loginUser",
		"/api/Users/registerUser",
		"/api/Users/sendPasswordResetEmail",
		"/api/Users/activateUserAccount/",
		"/api/Users/resetPassword",
		"/api/Users/changeTempPassword"
	];
 #endregion

 #region ServiceProviders
	$serviceProviders = [
		\Providers\FirebaseServiceProvider::class
	];
 #endregion
