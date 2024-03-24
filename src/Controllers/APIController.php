<?php

namespace Controllers;
use OpenApi\Annotations as OA;
/**
 * @OA\Info(
 *     title="Inventory managment system API",
 *     version="1.0",
 *     description="Inventory web based system for tracking items and stuff in company"
 *	 )
 */
class APIController
{
	/**
	 * @OA\Post(
	 *     path="/inventory-system-backend/api/loginUser",
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
	public function getAllUsers():int
	{
		return 5;
	}
}