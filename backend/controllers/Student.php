<?php

namespace Student;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="Inventory managment system API", version="1.0")
 */
class Student
{
	/**
	 * @OA\Get(
	 *     path="/api/resource/",
	 * 	   tags={"Student"},
	 *     @OA\Response(response="200", description="An example resource")
	 * )
	 */
	public function returnInteger():int
	{
		return 5;
	}
}