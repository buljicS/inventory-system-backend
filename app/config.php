<?php

#region Authorization config
$authPath = [
	"/api/Users/"
];

$ignorePath = [
	"/getDoc",
	"/api/Users/LoginUser",
	"/api/Users/RegisterUser",
	"/api/Users/SendPasswordResetEmail",
	"/api/Users/ActivateUserAccount/{token}",
	"/api/Users/ResetPassword"
];
#endregion
