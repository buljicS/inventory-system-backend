<?php

declare(strict_types=1);

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Services\EmailServices;
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
	private EmailServices $_email;

	public function __construct(UserServices $userServices, EmailServices $email)
	{
		$this->_user = $userServices;
		$this->_email = $email;
	}

	#region Main
	public function Index(Request $request, Response $response): Response
	{
		$response->getBody()->write(file_get_contents('../views/welcome_screen.html'));
		return $response;
	}

	public function GenerateDocs(Request $request, Response $response): Response
	{
		$openapi = Generator::scan(['../src'])->toJson();
		$file = fopen("../public/swagger/openapi.json", "wa+");
		fwrite($file, $openapi);
		fclose($file);
		$response->getBody()->write(file_get_contents("../public/swagger/openapi.json"));
		return $response
			->withHeader('Content-type', 'application/json');
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
		$requestBody = (array)$request->getParsedBody();
		if(!in_array(null, $requestBody, true)) {
			$newUser = $this->_user->RegisterUser($requestBody);
			$response->getBody()->write(json_encode($newUser));
			return $response
				->withHeader('Content-type', 'application/json')
				->withStatus(200);
		}
		$response->getBody()->write(json_encode("All fields are mandatory"));
		return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(200);

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
			->withStatus(200);
	}

	/**
	 * @OA\Post(
	 *     path="/api/Users/SendPasswordResetEmail",
	 *     tags={"Users"},
	 *     @OA\RequestBody(
	 *         description="Provide user email",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *             @OA\Schema(
	 *                 type="object",
	 *                 @OA\Property(
	 *                     property="email",
	 *                     type="string",
	 *                     example="example@email.com"
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
	 * )
	 */
	public function SendPasswordResetMail(Request $request, Response $response): Response
	{
		$response->getBody()->write("Done");
		return $response;
	}

	/**
	 * @OA\Post(
	 *     path="/api/Users/SendEmail",
	 *     tags={"Users"},
	 *     @OA\RequestBody(
	 *         description="Provide user email",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *             @OA\Schema(
	 *                 type="object",
	 *                 @OA\Property(
	 *                     property="sendTo",
	 *                     type="string",
	 *                     example="example@email.com"
	 *                 ),
	 *                      @OA\Property(
	 *                      property="ccTo",
	 *                      type="string",
	 *                      example="example@email.com"
	 *                  ),
	 *                      @OA\Property(
	 *                      property="subject",
	 *                      type="string",
	 *                      example="string"
	 *                  ),
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Sent"
	 *     ),
	 *     @OA\Response(
	 *         response=500,
	 *         description="Error"
	 *     ),
	 * )
	 */
	public function SendMail(Request $request, Response $response): Response
	{
		$requestBody = (array)$request->getParsedBody();
		$body = file_get_contents('../email-templates/ActivateAccount.html');
		$ccTo = $requestBody['ccTo'] ?? null;
		$sendMail = $this->_email->SendEmail($body, $requestBody['subject'], $requestBody['sendTo'], $ccTo);
		$response->getBody()->write(json_encode($sendMail, JSON_PRETTY_PRINT));
		return $response
			->withHeader('Content-type', 'application/json');
	}
	#endregion
}