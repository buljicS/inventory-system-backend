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

	public function GenerateJWTToken(int $userid): string
	{
		$headers = [
			'typ' => 'JWT',
			'alg' => 'HS256'
		];

		$payload = [
			'iss' => $_ENV['MAIN_URL_BE'],
			'aud' => $_ENV['MAIN_URL_FE'],
			'iat' => time(),
			'exp' => time() + 28800,
			'userid' => $userid
		];

		return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256', null, $headers);
	}

}