<?php

 #region PDO Options
	$pdoOptions = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false
	];
 #endregion

 #region Authorization middleware config
	$ignorePath = [
		"/",
		"/getDoc",
		"/api/Users/loginUser",
		"/api/Users/registerUser",
		"/api/Users/sendPasswordResetEmail",
		"/api/Users/activateUserAccount/",
		"/api/Users/resetPassword",
		"/api/Users/changeTempPassword",
		"/api/Admins/loginAdmin",
	];
 #endregion

 #region RBAC middleware config

	//ignore this middleware for all routes that are not going through JWT authorization
	$ignoreMiddlewareFor = $ignorePath;

	$roleBasedAccess = [
		"worker" => [
			"/api/Logs/logAccess",
			"/api/Users/updateUser",
			"/api/Users/setNewPassword",
		],
		"employer" => [

		]
];
#endregion

 #region ServiceProviders
	$serviceProviders = [
		\Providers\FirebaseServiceProvider::class
	];
 #endregion
