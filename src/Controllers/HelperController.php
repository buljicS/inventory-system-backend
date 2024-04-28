<?php

namespace Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Random\RandomException;

class HelperController
{
	/**
	 * @return string type of token
	 * @throws RandomException if it fails to generate token
	 */
	public function GenerateBasicToken(int $randomBytes):string
	{
		$bytes = random_bytes($randomBytes);
		return bin2hex($bytes);
	}

	/**
	 * @param string $email
	 * @param string $role
	 * @return string as JWT Token
	 */
	public function GenerateJWTToken(int $userid):string
	{
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
			'userid' => $userid
		];

		return JWT::encode($payload, $secret, 'HS256', null, $headers);
	}
}