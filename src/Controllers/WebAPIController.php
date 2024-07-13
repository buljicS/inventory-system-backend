<?php

declare(strict_types=1);

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Generator as Generator;

use Services\AdminServices as AdminServices;
use Services\UserServices as UserServices;
use Services\LogServices as LogServices;
use Services\FirebaseServices as FirebaseServices;
use Services\CompaniesServices as CompaniesServices;
use Services\RoomServices as RoomServices;

define("MAIN_URL", $_ENV['MAIN_URL_BE']);

/**
 * @OA\Info(
 *     title="Inventory management system API",
 *     version="1.1.0",
 *     description="Inventory web-based system for tracking items and stuff in company"
 * )
 * @OA\Server(
 *     url=MAIN_URL,
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter the Bearer Authorization string as follows: `Bearer Generated-JWT-Token`"
 * )
 */

class WebAPIController
{
	private UserServices $userServices;
	private AdminServices $adminServices;
	private LogServices $logServices;
	private FirebaseServices $firebaseServices;
	private CompaniesServices $companiesServices;
	private RoomServices $roomServices;

	public function __construct(UserServices $userServices,
								AdminServices $adminServices,
								LogServices $logServices,
								FirebaseServices $firebaseServices,
								CompaniesServices $companiesServices,
								RoomServices $roomServices)
	{
		$this->userServices = $userServices;
		$this->adminServices = $adminServices;
		$this->logServices = $logServices;
		$this->firebaseServices = $firebaseServices;
		$this->companiesServices = $companiesServices;
		$this->roomServices = $roomServices;
	}

	#region Main
	public function index(Request $request, Response $response): Response
	{
		$response->getBody()->write(file_get_contents('../templates/pages/main_screen.html'));
		return $response;
	}

	public function generateDocs(Request $request, Response $response): Response
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

	#region FirebaseStorage

	/**
	 * @OA\Get(
	 *     path="/api/FirebaseStorage/getAllFilesFromDir/{dir}",
	 *     operationId="getAllFilesFromDir",
	 *     description="Get all files from single directory",
	 *     tags={"FirebaseStorage"},
	 *     @OA\Parameter(
	 *        name="dir",
	 *        in="path",
	 *        required=true,
	 *        @OA\Schema(
	 *           type="string"
	 *        )
	 *      ),
	 *     @OA\Response(response="200", description="An example resource"),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function getAllFiles(Request $request, Response $response): Response
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
	 *     path="/api/Logs/logAccess",
	 *     operationId="logAccess",
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
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function logAccess(Request $request, Response $response): Response {
		$requestBody = (array)$request->getParsedBody();
		$resp = $this->logServices->logAccess($requestBody['isLoggedInSuccessfully'], $requestBody['workerId'], $requestBody['note']);
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Get(
	 *     path="/api/Logs/getAllLogs",
	 *     operationId="getAllLogs",
	 *     description="Get all previous logs",
	 *     tags={"Logs"},
	 *     @OA\Response(response="200", description="An example resource"),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function getAllLogs(Request $request, Response $response): Response
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
	 *     path="/api/Users/createUser",
	 *     operationId="createUser",
	 *     tags={"Users"},
	 *     @OA\RequestBody(
	 *         description="Create new user",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *             @OA\Schema(
	 *                 type="object",
	 *     				@OA\Property(
	 *                      property="worker_fname",
	 *                      type="string",
	 *                      example="string"
	 *                  ),
	 *     				@OA\Property(
	 *                       property="worker_lname",
	 *                       type="string",
	 *                       example="string"
	 *                 ),
	 *                 @OA\Property(
	 *                     property="worker_email",
	 *                     type="string",
	 *                     example="string"
	 *                 ),
	 *                 @OA\Property(
	 *                     property="company_id",
	 *                     type="integer",
	 *                     example=0
	 *                  )
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success"
	 *     ),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function createUser(Request $request, Response $response): Response
	{
		$newUserData = (array)$request->getParsedBody();
		$resp = $this->userServices->createNewUser($newUserData);
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Get(
	 *     path="/api/Users/getAllUsers",
	 *     operationId="getAllUsers",
	 *     description="Get all users and their information",
	 *     tags={"Users"},
	 *     @OA\Response(response="200", description="An example resource"),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function getAllUsers(Request $request, Response $response): Response
	{
		$resp = $this->userServices->GetAllUsers();
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Get(
	 *     path="/api/Users/getUserInfo/{worker_id}",
	 *     operationId="getSingleUser",
	 *     description="Get user info",
	 *     tags={"Users"},
	 *     @OA\Parameter(
	 *         name="worker_id",
	 *         in="path",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
	 *     @OA\Response(response="200", description="An example resource"),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function getUserInfo(Request $request, Response $response, array $args): Response {
		$userId = (int)$args['worker_id'];
		$resp = $this->userServices->getUserInfo($userId);
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Post(
	 *     path="/api/Users/registerUser",
	 *     operationId="registerUser",
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
	 *     ),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function registerUser(Request $request, Response $response): Response {
		$requestBody = (array)$request->getParsedBody();
		$newUser = $this->userServices->registerUser($requestBody);
		$response->getBody()->write(json_encode($newUser));
		return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(200);

	}

	/**
	 * @OA\Post(
	 *     path="/api/Users/loginUser",
	 *     operationId="loginUser",
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
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function loginUser(Request $request, Response $response): Response
	{
		$requestBody = (array)$request->getParsedBody();
		$authUser = $this->userServices->loginUser($requestBody);

		$response->getBody()->write(json_encode($authUser));
		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(200);
	}

	/**
	 * @OA\Post(
	 *     path="/api/Users/sendPasswordResetEmail",
	 *     operationId="sendPasswordResetEmail",
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
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function sendPasswordResetMail(Request $request, Response $response): Response
	{
		$requestBody = (array)$request->getParsedBody();
		$resetMail = $this->userServices->sendPasswordResetMail($requestBody['email']);
		$response->getBody()->write(json_encode($resetMail));
		return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(200);
	}

	/**
	 * @OA\Get(
	 *     path="/api/Users/activateUserAccount/{token}",
	 *     operationId="activateUserAccount",
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
	 *     @OA\Response(response="200", description="An example resource"),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function activateUserAccount(Request $request, Response $response, array $args): Response
	{
		$token = $args['token'];
		$actResponse = $this->userServices->activateUser($token);
		return $response
			->withHeader("Location", "{$_ENV['MAIN_URL_FE']}/login?status=$actResponse")
			->withStatus(302);
	}

	/**
	 * @OA\Put(
	 *     path="/api/Users/updateUser",
	 *     operationId="updateUser",
	 *     tags={"Users"},
	 *     @OA\RequestBody(
	 *         description="Update user informations",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *             @OA\Schema(
	 *                 type="object",
	 *     			   @OA\Property (
	 *     			     property="worker_id",
	 *     				 type="int",
	 *     				 example="0"
	 *     			   ),
	 *                 @OA\Property(
	 *                     property="phone_number",
	 *                     type="string",
	 *                     example="+12345"
	 *                 ),
	 *                 @OA\Property(
	 *                     property="company_id",
	 *                     type="int",
	 *                     example="0"
	 *                  ),
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success"
	 *     ),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function updateUserData(Request $request, Response $response): Response
	{
		$requestBody = (array)$request->getParsedBody();
		$updatedUser = $this->userServices->updateUserData($requestBody);
		$response->getBody()->write(json_encode($updatedUser));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Post(
	 *     path="/api/Users/resetPassword",
	 *     operationId="resetPassword",
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
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function resetPassword(Request $request, Response $response): Response
	{
		$requestBody = (array)$request->getParsedBody();
		$actResponse = $this->userServices->resetPassword($requestBody['hash'], $requestBody['newPassword']);
		$response->getBody()->write(json_encode($actResponse));
		return $response
			->withHeader("Content-type", "application/json");
	}

	/**
	 * @OA\Post(
	 *     path="/api/Users/setNewPassword",
	 *     operationId="setNewPassword",
	 *     tags={"Users"},
	 *     @OA\RequestBody(
	 *         description="This endpoint servers as a option for loged user to reset password",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *             @OA\Schema(
	 *                 type="object",
	 *     				@OA\Property(
	 *                      property="worker_id",
	 *                      type="int",
	 *                      example="0"
	 *                  ),
	 *                 @OA\Property(
	 *                     property="old_password",
	 *                     type="string",
	 *                     example="string"
	 *                 ),
	 *                 @OA\Property(
	 *                     property="new_password",
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
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function setNewPassword(Request $request, Response $response): Response
	{
		$requestBody = (array)$request->getParsedBody();
		$resp = $this->userServices->setNewPassword($requestBody);
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Put(
	 *     path="/api/Users/changeTempPassword",
	 *     operationId="changeTempPassword",
	 *     tags={"Users"},
	 *     @OA\RequestBody(
	 *         description="Endpoint for chaning temp password that has been set for user (employer) by admin",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *             @OA\Schema(
	 *                 type="object",
	 *     				@OA\Property(
	 *                      property="worker_id",
	 *                      type="int",
	 *                      example="0"
	 *                  ),
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
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function changeTempPassword(Request $request, Response $response): Response
	{
		$requestBody = (array)$request->getParsedBody();
		$resp = $this->userServices->changeTempPassword($requestBody);
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Delete(
	 *     path="/api/Users/banUser/{worker_id}",
	 *     operationId="banUser",
	 *     description="Endpoint for admin to ban user account",
	 *     tags={"Users"},
	 *     @OA\Parameter(
	 *         description="ID of user to ban",
	 *         in="path",
	 *         name="worker_id",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="integer",
	 *             format="int64"
	 *         )
	 *     ),
	 *     @OA\Response(
	 *        response=200,
	 *        description="Success"
	 *      ),
	 *      @OA\Response(
	 *        response=404,
	 *        description="User not found"
	 *      ),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function banUser(Request $request, Response $response, array $args): Response {
		$worker_id = (int)$args['worker_id'];
		$resp = $this->userServices->banUser($worker_id);
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Put(
	 *     path="/api/Users/revokeUserAccess/{worker_id}",
	 *     operationId="revokeUserAccess",
	 *     description="Endpoint for admin to revoke ban for banned users",
	 *     tags={"Users"},
	 *		@OA\Parameter(
	 *          description="ID of user to revoke ban",
	 *          in="path",
	 *          name="worker_id",
	 *          required=true,
	 *          @OA\Schema(
	 *              type="integer",
	 *              format="int64"
	 *          )
	 *      ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success"
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *     	   description="User not found"
	 *     ),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function revokeUserAccess(Request $request, Response $response, array $args): Response
	{
		$worker_id = (int)$args['worker_id'];
		$resp = $this->userServices->revokeUserAccess($worker_id);
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Put(
	 *     path="/api/Users/updateUserByAdmin",
	 *     operationId="updateUserByAdmin",
	 *     description="Endpoint for admin to update information about users",
	 *     tags={"Users"},
	 *     @OA\RequestBody(
	 *         description="New company information",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *             @OA\Schema(
	 *                 type="object",
	 *                   @OA\Property (
	 *                     property="company_id",
	 *                     type="int",
	 *                     example=0
	 *                   ),
	 *                 @OA\Property(
	 *                     property="worker_id",
	 *                     type="integer",
	 *                     example=0
	 *                 ),
	 *                 @OA\Property(
	 *                      property="worker_fname",
	 *                      type="string",
	 *                      example="Name"
	 *                  ),
	 *                 @OA\Property(
	 *                     property="worker_lname",
	 *                     type="string",
	 *                     example="Last"
	 *                  ),
	 *                 @OA\Property(
	 *                      property="role",
	 *                      type="string",
	 *                      example="role"
	 *                   ),
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success"
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *     	   description="User not found"
	 *     ),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function updateUserByAdmin(Request $request, Response $response, array $args): Response
	{
		$requestBody = (array)$request->getParsedBody();
		$resp = $this->userServices->updateUserByAdmin($requestBody);
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	#endregion

	#region Companies

	/**
	 * @OA\Post(
	 *     path="/api/Companies/addCompany",
	 *     operationId="addCompany",
	 *     tags={"Companies"},
	 *     @OA\RequestBody(
	 *         description="Create new company",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *             @OA\Schema(
	 *                 type="object",
	 *     				@OA\Property(
	 *                      property="company_name",
	 *                      type="string",
	 *                      example="string"
	 *                  ),
	 *     				@OA\Property(
	 *                       property="company_mail",
	 *                       type="string",
	 *                       example="string"
	 *                 ),
	 *                 @OA\Property(
	 *                     property="company_state",
	 *                     type="string",
	 *                     example="string"
	 *                 ),
	 *                 @OA\Property(
	 *                     property="company_address",
	 *                     type="string",
	 *                     example="string"
	 *                 )
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success"
	 *     ),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function addCompany(Request $request, Response $response): Response
	{
		$requestBody = (array)$request->getParsedBody();
		$newCompany = $this->companiesServices->addNewCompany($requestBody);
		$response->getBody()->write(json_encode($newCompany));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Get(
	 *     path="/api/Companies/getAllCompanies",
	 *     operationId="getAllCompanies",
	 *     description="Get all companies and their information",
	 *     tags={"Companies"},
	 *     @OA\Response(response="200", description="An example resource"),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function getAllCompanies(Request $request, Response $response): Response
	{
		$resp = $this->companiesServices->getAllCompanies();
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Get(
	 *     path="/api/Companies/getCompanyById",
	 *     operationId="getCompanyById",
	 *     description="Get company by id",
	 *     tags={"Companies"},
	 *     @OA\Response(response="200", description="An example resource"),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function getCompanyById(Request $request, Response $response, array $args): Response
	{
		$companyId = (int)$args['company_id'];
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Put(
	 *     path="/api/Companies/updateCompany",
	 *     operationId="updateCompany",
	 *     tags={"Companies"},
	 *     @OA\RequestBody(
	 *         description="Update company information",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *             @OA\Schema(
	 *                 type="object",
	 *     			   @OA\Property (
	 *     			     property="company_id",
	 *     				 type="int",
	 *     				 example="0"
	 *     			   ),
	 *                 @OA\Property(
	 *                     property="company_name",
	 *                     type="string",
	 *                     example="NameCo"
	 *                 ),
	 *                 @OA\Property(
	 *                      property="company_mail",
	 *                      type="string",
	 *                      example="officecompany@domain.com"
	 *                  ),
	 *                 @OA\Property(
	 *                     property="company_state",
	 *                     type="string",
	 *                     example="USA"
	 *                  ),
	 *                 @OA\Property(
	 *                      property="company_address",
	 *                      type="string",
	 *                      example="Address 1"
	 *                   ),
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success"
	 *     ),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function updateCompany(Request $request, Response $response): Response
	{
		$newCompanyData = (array)$request->getParsedBody();
		$resp = $this->companiesServices->updateCompany($newCompanyData);
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Delete(
	 *     path="/api/Companies/deleteCompany/{company_id}",
	 *     operationId="deleteCompany",
	 *     tags={"Companies"},
	 *     @OA\Parameter(
	 *         description="ID of company to delete",
	 *         in="path",
	 *         name="company_id",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="integer",
	 *             format="int64"
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="Company not found"
	 *     ),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function deleteCompany(Request $request, Response $response, array $args): Response
	{
		$company_id = (int)$args['company_id'];
		$resp = $this->companiesServices->deleteCompany($company_id);
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Put(
	 *     path="/api/Companies/restoreCompany/{company_id}",
	 *     operationId="restoreCompany",
	 *     description="Endpoint for admin to restored deleted company",
	 *     tags={"Companies"},
	 *		@OA\Parameter(
	 *          description="ID of company that needs to be restored",
	 *          in="path",
	 *          name="company_id",
	 *          required=true,
	 *          @OA\Schema(
	 *              type="integer",
	 *              format="int64"
	 *          )
	 *      ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success"
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *     	   description="Company not found"
	 *     ),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function restoreCompany(Request $request, Response $response, array $args): Response
	{
		$company_id = (int)$args['company_id'];
		$resp = $this->companiesServices->restoreCompany($company_id);
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}
	#endregion

	#region Rooms

	/**
	 * @OA\Post(
	 *     path="/api/Rooms/addRoom",
	 *     operationId="addRoom",
	 *     tags={"Rooms"},
	 *     @OA\RequestBody(
	 *         description="Create new room",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *             @OA\Schema(
	 *                 type="object",
	 *     				@OA\Property(
	 *                      property="company_id",
	 *                      type="integer",
	 *                      example=0
	 *                  ),
	 *     				@OA\Property(
	 *                       property="room_name",
	 *                       type="string",
	 *                       example="string"
	 *                 ),
	 *                 @OA\Property(
	 *                     property="room_number",
	 *                     type="integer",
	 *                     example=0
	 *                 ),
	 *                 @OA\Property(
	 *                     property="room_description",
	 *                     type="string",
	 *                     example="string"
	 *                 )
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success"
	 *     ),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function addRoom(Request $request, Response $response): Response
	{
		$requestBody = (array)$request->getParsedBody();
		$resp = $this->roomServices->addNewRoom($requestBody);
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');

	}

	/**
	 * @OA\Get(
	 *     path="/api/Rooms/getAllRooms",
	 *     operationId="getAllRooms",
	 *     description="Get all rooms",
	 *     tags={"Rooms"},
	 *     @OA\Response(response="200", description="An example resource"),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function getAllRooms(Request $request, Response $response): Response
	{
		$resp = $this->roomServices->getAllRooms();
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Get(
	 *     path="/api/Rooms/getAllRoomsByCompanyId/{company_id}",
	 *     operationId="getAllRoomsByCompanyId",
	 *     description="Get all rooms by company",
	 *     tags={"Rooms"},
	 *     @OA\Parameter(
	 *         name="company_id",
	 *         in="path",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
	 *     @OA\Response(response="200", description="An example resource"),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function getAllRoomsByCompanyId(Request $request, Response $response, array $args): Response
	{
		$company_id = (int)$args['company_id'];
		$resp = $this->roomServices->getAllRoomsByCompanyId($company_id);
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}

	/**
	 * @OA\Delete(
	 *     path="/api/Rooms/deleteRoom/{room_id}",
	 *     operationId="deleteRoom",
	 *     description="Delete inactive rooms (rooms that have no active inventory event)",
	 *     tags={"Rooms"},
	 *     @OA\Parameter(
	 *         description="ID of room to be deleted",
	 *         in="path",
	 *         name="room_id",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="integer",
	 *             format="int64"
	 *         )
	 *     ),
	 *     @OA\Response(
	 *        response=200,
	 *        description="Success"
	 *      ),
	 *      @OA\Response(
	 *        response=404,
	 *        description="Room not found"
	 *      ),
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function deleteRoom(Request $request, Response $response, array $args): Response
	{
		$room_id = (int)$args['room_id'];
		$resp = $this->roomServices->deleteRoom($room_id);
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}


	#endregion

	#region Admins

	/**
	 * @OA\Post(
	 *     path="/api/Admins/loginAdmin",
	 *     operationId="loginAdmin",
	 *     tags={"Admins"},
	 *     @OA\RequestBody(
	 *         description="Authenticate admin",
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
	 *     security={{"bearerAuth": {}}}
	 * )
	 */
	public function loginAdmin(Request $request, Response $response): Response {
		$credentials = (array)$request->getParsedBody();
		$resp = $this->adminServices->loginAdmin($credentials);
		$response->getBody()->write(json_encode($resp));
		return $response
			->withHeader('Content-type', 'application/json');
	}
	#endregion

	#region TestEndpoints

	/**
	 * @OA\Post(
	 *     path="/api/Tests/listTest",
	 *     summary="Create a Test",
	 *     tags={"Tests"},
	 *     @OA\RequestBody(
	 *        required = true,
	 *        description = "Data packet for Test",
	 *        @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(
	 *                property="testItems",
	 *                type="array",
	 *                example={{
	 *                  "firstName": "Bob",
	 *                  "lastName": "Fanger",
	 *                  "company": "Triple",
	 *                  "id": "808",
	 *                }, {
	 *                  "firstName": "",
	 *                  "lastName": "",
	 *                  "company": "",
	 *                  "id": ""
	 *                }},
	 *                @OA\Items(
	 *                      @OA\Property(
	 *                         property="firstName",
	 *                         type="string",
	 *                         example=""
	 *                      ),
	 *                      @OA\Property(
	 *                         property="lastName",
	 *                         type="string",
	 *                         example=""
	 *                      ),
	 *                      @OA\Property(
	 *                         property="companyId",
	 *                         type="string",
	 *                         example=""
	 *                      ),
	 *                      @OA\Property(
	 *                         property="accountNumber",
	 *                         type="number",
	 *                         example="000123"
	 *                      ),
	 *                      @OA\Property(
	 *                         property="netPay",
	 *                         type="number",
	 *                         example="12345"
	 *                      ),
	 *                ),
	 *             ),
	 *        ),
	 *     ),
	 *     @OA\Response(
	 *        response="200",
	 *        description="Successful response",
	 *     ),
	 * )
	 */
	public function listTest(Request $request, Response $response, array $args): Response
	{
		return $response;
	}
	#endregion

}