<?php

namespace Controllers;
use OpenApi\Annotations as OA;
/**
 * @OA\Info(title="Inventory managment system API", version="1.0")
 */
class APIController
{
	/**
	 * @OA\Post(
	 *     path="/api/loginUser",
	 *     summary="Create a new resource",
	 *     tags={"Resource"},
	 *     @OA\RequestBody(
	 *         required=true,
	 *         description="Provide data to create a new resource",
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
	 *         description="Resource created successfully"
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Bad request, invalid input provided"
	 *     )
	 * )
	 */
	public function getAllUsers():int
	{
		return 5;
	}
}