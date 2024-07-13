<?php

namespace Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class RoleBasedAccessControls implements MiddlewareInterface
{
	private array $ignoreFor;
	private array $allowedByRoles;

	public function __construct(array $allowedByRoles, array $ignoreFor)
	{
		$this->ignoreFor = $ignoreFor;
		$this->allowedByRoles = $allowedByRoles;
	}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		//get currently requested endpoint
		$requestRoute = $request->getUri()->getPath();

		//if request is set to be ignored, proceed
		if(in_array($requestRoute, $this->ignoreFor)) return $handler->handle($request);

		$requestToken = $request->getAttribute('decoded-jwt');
		$requestedBy = $requestToken['role'];

		$allowedRoutesByRole = $this->allowedByRoles[$requestedBy];

		if(in_array($requestRoute, $allowedRoutesByRole) || $requestedBy == 'admin')
			return $handler->handle($request);

		else {
			//create response payload
			$data['status'] = "Unauthorized";
			$data['message'] = "Ops, looks like you don't have enough permissions to access this resource.";

			//create response interface
			$response = new Response();
			$response->getBody()->write(json_encode($data));

			//return response as json
			return $response
				->withHeader('Content-Type', 'application/json')
				->withHeader('Access-Control-Allow-Origin', "{$_ENV['MAIN_URL_FE']}")
				->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
				->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
				->withStatus(401);
		}
	}
}