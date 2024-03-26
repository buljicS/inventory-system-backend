<?php

declare(strict_types=1);

namespace Controllers;

use OpenApi\Annotations\OpenApi;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Services\UserServices as UserServices;


/**
 * @OA\Info(
 *     title="Inventory managment system API",
 *     version="1.0",
 *     description="Inventory web based system for tracking items and stuff in company"
 *	 )
 */
class APIController
{
	private $_user;

	public function __construct(UserServices $userServices)
	{
		$this->_user = $userServices;
	}

	#region Main

	public function Index(Request $request, Response $response): Response
	{
		$response->getBody()->write("Hello World");
		return $response;
	}

	public function GenerateDocs(Request $request, Response $response): Response
	{
		$openapi = \OpenApi\Generator::scan(['../']);
		$jsonDoc = fopen("../../public/swagger/swagger-docs.json", "w");
		fwrite($jsonDoc, $openapi->toJson());
		fclose($jsonDoc);
		$response->getBody()->write($openapi->toJson());
		if($_ENV['IS_DEV']) {
			return $response
				->withHeader('Location', '../../public/swagger')
				->withStatus(302);
		}
		return $response
			->withHeader('Location', '.')
			->withStatus(401);
	}
	#endregion

	#region Users

	/**
	 * @OA\Post(
	 *     path="/inventory-system-backend/api/LoginUser",
	 *     summary="User login",
	 *     tags={"Workers"},
	 *     @OA\RequestBody(
	 *         required=true,
	 *         description="Enter user email and password",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *             @OA\Schema(
	 *                 type="object",
	 *                 @OA\Property(
	 *                     property="email",
	 *                     type="string",
	 *                     example="example@email.com"
	 *                 ),
	 *                 @OA\Property(
	 *                     property="password",
	 *                     type="string",
	 *                     example="Your password"
	 *                 ),
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="User found"
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="User not found"
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Wrong creditentials"
	 *      ),
	 * )
	 */
	public function LoginUser(Request $request, Response $response): Response
	{
		$requestBody = (array)$request->getParsedBody();
		$authUser = $this->_user->AuthenticateUser($requestBody['email'], $requestBody['password']);
		$response->getBody()->write(json_encode($authUser));
		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(intval($authUser['status']));
	}

	#endregion
}