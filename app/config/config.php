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
	"/api/Users/"
];

$ignorePath = [
	"/getDoc",
	"/api/Users/LoginUser",
	"/api/Users/RegisterUser",
	"/api/Users/SendPasswordResetEmail",
	"/api/Users/ActivateUserAccount/",
	"/api/Users/ResetPassword"
];
#endregion

#region ServiceProviders
$serviceProviders = [
	\Providers\FirebaseServiceProvider::class,
	\Providers\PDOServiceProvider::class
];
#endregion
