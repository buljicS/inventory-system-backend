<?php

namespace Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class HelperController
{
	public function GenerateBasicToken(int $randomBytes):string
	{
		$bytes = random_bytes($randomBytes);
		return bin2hex($bytes);
	}

	public function GenerateJWTToken(string $email, string $role):string
	{
		$authJWT = "";
		$mainURLBE = $_ENV['MAIN_URL_BE'];
		$mainURLFE = $_ENV['MAIN_URL_FE'];
		$secret = $_ENV['JWT_SECRET'];

		$headers = [
			'typ' => 'JWT',
			'alg' => 'HS256'
		];

		$payload = [
			'iss' => $mainURLBE,
			'aud' => $mainURLFE,
			'iat' => time(),
			'exp' => time() + 3600,
			'user' => $email,
			'role' => $role
		];

		return JWT::encode($payload, $secret, 'HS256', null, $headers);
	}
}