<?php

declare(strict_types=1);

use Slim\App as Slim;
use Middlewares\RoleBasedAccessControls as RBAC;

return function(Slim $app) {
	require __DIR__ . '/../config/config.php';
	$app->add(new RBAC($roleBasedAccess, $ignoreMiddlewareFor));
};

