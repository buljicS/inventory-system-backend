<?php

namespace Services;

use Repositories\AdminRepository as AdminRepository;
use Utilities\ValidatorUtility as Validator;
use Utilities\TokenUtility as Token;

class AdminServices
{
	private readonly AdminRepository $adminRepository;
	private readonly Validator $validator;
	private readonly Token $token;


	public function __construct(AdminRepository $adminRepository, Validator $validator, Token $token)
	{
		$this->adminRepository = $adminRepository;
		$this->validator = $validator;
		$this->token = $token;
	}

	public function LoginAdmin(array $credentials): array {
		$isValid = $this->validator->validateAdminCredentials($credentials);

		if (!$isValid)
			return $isValid;

		$admin = $this->adminRepository->GetAdminByEmail($credentials);

		return match (true) {
			$admin === false || !password_verify($credentials['admin_password'], $admin['admin_password']) => [
				'status' => 401,
				'message' => 'Unauthorized',
				'description' => 'Invalid credentials',
			],

			default => [
				'status' => 200,
				'userId' => $admin['admin_id'],
				'userEmail' => $admin['admin_username'],
				'userRole' => 'admin',
				'token' => $this->token->GenerateJWTToken($admin['admin_id'])
			]
		};
	}
}