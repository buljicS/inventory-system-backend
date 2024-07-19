<?php

namespace Services;

use Services\UsersServices as UsersServices;
use Repositories\AdminsRepository as AdminsRepository;
use Services\CompaniesServices as CompaniesServices;
use Utilities\ValidatorUtility as Validator;
use Utilities\TokenUtility as Token;

class AdminsServices
{
	private readonly AdminsRepository $adminRepository;
	private readonly Validator $validator;
	private readonly Token $token;
	private readonly CompaniesServices $companiesServices;
	private readonly UsersServices $userServices;


	public function __construct(UsersServices $userServices, AdminsRepository $adminRepository, Validator $validator, Token $token, CompaniesServices $companiesServices)
	{
		$this->adminRepository = $adminRepository;
		$this->validator = $validator;
		$this->token = $token;
		$this->companiesServices = $companiesServices;
		$this->userServices = $userServices;
	}


	public function loginAdmin(array $credentials): array {
		$isValid = $this->validator->validateAdminCredentials($credentials);

		if (!$isValid)
			return $isValid;

		$admin = $this->adminRepository->getAdminByEmail($credentials);
		$admin['role'] = 'admin';


		return match (true) {
			$admin === false || !password_verify($credentials['password'], $admin['admin_password']) => [
				'status' => 401,
				'message' => 'Unauthorized',
				'description' => 'Invalid credentials',
			],

			default => [
				'status' => 200,
				'token' => $this->token->GenerateJWTToken($admin)
			]
		};
	}
}