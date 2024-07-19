<?php

namespace Utilities;

use Firebase\JWT\JWT;
use Random\RandomException;

class TokenUtility
{
	/**
	 * @throws RandomException on random_bytes
	 */
	public function GenerateBasicToken(int $randomBytes):string
	{
		$bytes = random_bytes($randomBytes);
		return bin2hex($bytes);
	}

	public function GenerateJWTToken(array $loggedUser): string
	{
		$headers = [
			'typ' => 'JWT',
			'alg' => 'HS256'
		];

		if($loggedUser["role"] == "admin"){
			$payload = [
				'iss' => $_ENV['MAIN_URL_BE'],
				'aud' => $_ENV['MAIN_URL_FE'],
				'iat' => time(),
				'exp' => time() + 28800,
				'client_id' => $loggedUser["admin_id"],
				'email' => $loggedUser["admin_username"],
				'role' => $loggedUser["role"]
			];
		}
		else {
			$payload = [
				'iss' => $_ENV['MAIN_URL_BE'],
				'aud' => $_ENV['MAIN_URL_FE'],
				'iat' => time(),
				'exp' => time() + 28800,
				'client_id' => $loggedUser['worker_id'],
				'name' => $loggedUser['worker_fname'] . " " . $loggedUser['worker_lname'],
				'email' => $loggedUser['worker_email'],
				'phone_number' => $loggedUser['phone_number'],
				'picture' => $loggedUser['picture'], //custom_field
				'role' => $loggedUser['role'],
				'company' => $loggedUser['company'] //custom_filed
			];
		}


		return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256', null, $headers);
	}

}