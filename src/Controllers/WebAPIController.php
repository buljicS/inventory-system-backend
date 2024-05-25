<?php

declare(strict_types=1);

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Generator as Generator;

use Services\UserServices as UserServices;
use Services\LogServices as LogServices;
use Services\FirebaseServices as FirebaseServices;



/**
 * @OA\Info(
 *     title="Inventory management system API",
 *     version="1.0.0",
 *     description="Inventory web based system for tracking items and stuff in company"
 *	 )
 * @OA\Server(
 *      url="http://www.insystem-api.localhost/",
 *  )
 *
 * @OA\SecurityScheme (
 *      securityScheme="Bearer",
 *      type="http",
 *      scheme="bearer
 *      bearerFormat="JWT",
 *      description="Enter the Bearer Authorization string as following: `Bearer Generated-JWT-Token`",
 *      name="Authorization",
 *      in="header",
 *      @OA\Flow(
 *          flow="password",
 *          tokenUrl="/oauth/token",
 *          refreshUrl="/oauth/token/refresh",
 *          scopes={}
 *      )
 *  )
 */

class WebAPIController
{
	private UserServices $userServices;
	private LogServices $logServices;
	private FirebaseServices $firebaseServices;

	public function __construct(UserServices $userServices, LogServices $logServices, FirebaseServices $firebaseServices)
	{
		$this->userServices = $userServices;
		$this->logServices = $logServices;
		$this->firebaseServices = $firebaseServices;
	}

	#region Main
	public function Index(Request $request, Response $response): Response
	{
		$response->getBody()->write(file_get_contents('../templates/pages/welcome_screen.html'));
		return $response;
	}

	public function GenerateDocs(Request $request, Response $response): Response
	{
		$openapi = Generator::scan(['../src'])->toJson();
		$file = fopen("../public/swagger/openapi.json", "wa+");
		fwrite($file, $openapi);
		fclose($file);
		$response->getBody()->write(file_get_contents(__DIR__ . "/../public/swagger/openapi.json"));
		return $response
			->withHeader('Content-type', 'application/json');
	}
	#endregion

	#region FirebaseStorage
	/**
	 * @OA\Get(
	 *     path="/api/FirebaseStorage/GetAllFilesFromDir/{dir}",
	 *     description="Get all files from single directory",
	 *     tags={"FirebaseStorage"},
	 *     @OA\Response(response="200", description="An example resource")
	 * )
	 */
	public function GetAllFiles(Request $request, Response $response): Response
	{
		$resp = $this->firebaseServices->getFirebaseInstance();
		$response->getBody()->write(json_encode($resp->name()));
		return $response
			->withHeader('Content-type', 'application/json');
	}
	#endregion

	#region AccessLogs

	/**
	 * @OA\Post(
	 *     path="/api/Logs/LogAccess",
	 *     tags={"Logs"},
	 *     @OA\RequestBody(
	 *         description="Log user access on login",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *             @OA\Schema(
	 *                 type="object",
	 *                 @OA\Property(
	 *                     property="isLoggedInSuccessfully",
	 *                     type="bool",
	 *                     example=true
	 *                 ),
	 *                 @OA\Property(
	 *                     property="workerId",
	 *                     type="integer",
	 *                     example="0"
	 *                  ),
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success"
	 *     ),
	 * )
	 */
	public function LogAccess(Request $request, Response $response): Response {
		$requestBody = (array)$request->getParsedBody();
		$resp = $this->logServices->LogAccess($requestBody['isLoggedInSuccessfully'], $requestBody['workerId'], $requestBody['note']);
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Get(
	 *     path="/api/Logs/GetAllLogs",
	 *     description="Get all previous logs",
	 *     tags={"Logs"},
	 *     @OA\Response(response="200", description="An example resource")
	 * )
	 */
	public function GetAllLogs(Request $request, Response $response): Response
	{
		$resp = $this->logServices->GetAllLogs();
		$response->getBody()->write(json_encode($resp));
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
		$newUser = $this->userServices->RegisterUser($requestBody);
		$response->getBody()->write(json_encode($newUser));
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
		$authUser = $this->userServices->LoginUser($requestBody);

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
		$requestBody = (array)$request->getParsedBody();
		$resetMail = $this->userServices->SendPasswordResetMail($requestBody['email']);
		$response->getBody()->write(json_encode($resetMail));
		return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(200);
	}

	/**
	 * @OA\Get(
	 *     path="/api/Users/ActivateUserAccount/{token}",
	 *     description="Do not run this route from swagger since it's supposed to redirect user to specific page. <br/> Swagger will return `NetworkError` cause redirection can not happen",
	 *     tags={"Users"},
	 *     @OA\Parameter(
	 *         name="token",
	 *         in="path",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),
	 *     @OA\Response(response="200", description="An example resource")
	 * )
	 */
	public function ActivateUserAccount(Request $request, Response $response, array $args): Response
	{
		$token = $args['token'];
		$actResponse = $this->userServices->ActivateUser($token);
		return $response
			->withHeader("Location", "{$_ENV['MAIN_URL_FE']}/login?status=$actResponse")
			->withStatus(302);
	}

	/**
	 * @OA\Put(
	 *     path="/api/Users/UpdateUser",
	 *     tags={"Users"},
	 *     @OA\RequestBody(
	 *         description="Update user informations",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *             @OA\Schema(
	 *                 type="object",
	 *     			   @OA\Property (
	 *     			     property="userId",
	 *     				 type="integer",
	 *     				 example="0"
	 *     			   ),
	 *                 @OA\Property(
	 *                     property="phoneNumber",
	 *                     type="string",
	 *                     example="string"
	 *                 ),
	 *                 @OA\Property(
	 *                     property="companies",
	 *                     type="string",
	 *                     example="string"
	 *                  ),
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success"
	 *     ),
	 * )
	 */
	public function UpdateUserInfo(Request $request, Response $response): Response
	{
		$requestBody = (array)$request->getParsedBody();
		$response->getBody()->write(json_encode("Hi from this one"));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Post(
	 *     path="/api/Users/ResetPassword",
	 *     tags={"Users"},
	 *     @OA\RequestBody(
	 *         description="Reset password from email",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *             @OA\Schema(
	 *                 type="object",
	 *                 @OA\Property(
	 *                     property="hash",
	 *                     type="string",
	 *                     example="string"
	 *                 ),
	 *                 @OA\Property(
	 *                     property="newPassword",
	 *                     type="string",
	 *                     example="string"
	 *                  ),
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success"
	 *     ),
	 * )
	 */
	public function ResetPassword(Request $request, Response $response): Response
	{
		$requestBody = (array)$request->getParsedBody();
		$actResponse = $this->userServices->ResetPassword($requestBody['hash'], $requestBody['newPassword']);
		$response->getBody()->write(json_encode($actResponse));
		return $response
			->withHeader("Content-type", "application/json");
	}

	/**
	 * @OA\Post(
	 *     path="/api/Users/SetNewPassword",
	 *     tags={"Users"},
	 *     @OA\RequestBody(
	 *         description="This endpoint servers as a option for loged user to reset password",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *             @OA\Schema(
	 *                 type="object",
	 *                 @OA\Property(
	 *                     property="oldPassword",
	 *                     type="string",
	 *                     example="string"
	 *                 ),
	 *                 @OA\Property(
	 *                     property="newPassword",
	 *                     type="string",
	 *                     example="string"
	 *                  ),
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success"
	 *     ),
	 * )
	 */
	public function SetNewPassword(Request $request, Response $response): Response
	{
		$requestBody = (array)$request->getParsedBody();
		$response->getBody()->write(json_encode("Hi from this one"));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	#endregion




}