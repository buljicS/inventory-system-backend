<?php

declare(strict_types=1);

namespace Controllers;

use OpenApi\Annotations\OpenApi;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Services\UserServices as UserServices;
use OpenApi\Generator as Generator;


/**
 * @OA\Info(
 *     title="Inventory management system API",
 *     version="1.0.0",
 *     description="Inventory web based system for tracking items and stuff in company"
 *	 )
 * @OA\Server(
 *      url="http://www.insystem-api.localhost/",
 *  )
 */
class APIController
{
	private UserServices $_user;

	public function __construct(UserServices $userServices)
	{
		$this->_user = $userServices;
	}

	#region Main
	public function Index(Request $request, Response $response): Response
	{
		$response->getBody()->write(file_get_contents('../views/welcome_screen.html'));
		return $response;
	}
	#endregion

	#region Users

	/**
	 * @OA\Post(
	 *     path="/api/Users/RegisterUser",
	 *     tags={"Users"},
	 *     @OA\RequestBody(
	 *         description="Create new user account",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *             @OA\Schema(
	 *                 type="object",
	 *     				@OA\Property(
	 *                      property="firstName",
	 *                      type="string",
	 *                      example="string"
	 *                  ),
	 *     				@OA\Property(
	 *                       property="lastName",
	 *                       type="string",
	 *                       example="string"
	 *                 ),
	 *                 @OA\Property(
	 *                     property="email",
	 *                     type="string",
	 *                     example="string"
	 *                 ),
	 *                 @OA\Property(
	 *                     property="password",
	 *                     type="string",
	 *                     example="string"
	 *                 ),
	 *     			  @OA\Property (
	 *                      property="phoneNumber",
	 *                      type="string",
	 *                      example="string"
	 *     			  )
	 *
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success"
	 *     )
	 * )
	 */
	public function RegisterUser(Request $request, Response $response): Response {
		return $response;
	}

	/**
	 * @OA\Post(
	 *     path="/api/Users/LoginUser",
	 *     tags={"Users"},
	 *     @OA\RequestBody(
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
	 *         description="Success"
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="Not found"
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Wrong credentials"
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