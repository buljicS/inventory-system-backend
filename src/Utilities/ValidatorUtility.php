<?php

namespace Utilities;

use Valitron\Validator as vValidator;


class ValidatorUtility
{
	private vValidator $_vValidator;

	public function __construct(vValidator $vValidator)
	{
		$this->_vValidator = $vValidator;
	}

	#region UserValidation
	public function validateRegisterUserInput(array $newUserInput): bool|array
	{
		$this->_vValidator = new vValidator($newUserInput);
		$this->_vValidator->rules(
			[
				'required' => [
					['firstName'],
					['lastName'],
					['email'],
					['password']
				],
				'email' => [
					['email']
				]
			]
		);

		if ($this->_vValidator->validate()) {
			return true;
		}

		return [
			'status' => 202,
			'message' => 'Accepted',
			'description' => $this->_vValidator->errors()
		];

	}

	public function validateLoginUserInput(array $userInput): bool|array
	{
		$this->_vValidator = new vValidator($userInput);
		$this->_vValidator->rules(
			[
				'required' => [
					['email'],
					['password']
				],
				'email' => [
					['email']
				]
			]
		);

		if($this->_vValidator->validate()) {
			return true;
		}

		return [
			'status' => 202,
			'message' => 'Accepted',
			'description' => $this->_vValidator->errors()
		];
	}

	public function validateNewPasswordData(array $userInfo): bool|array
	{
		$this->_vValidator = new vValidator($userInfo);
		$this->_vValidator->rules([
				'required' => [
					['old_password'],
					['new_password'],
					['worker_id']
				]
			]
		);

		if($this->_vValidator->validate()) return true;

		return [
			'status' => 202,
			'message' => 'Accepted',
			'description' => $this->_vValidator->errors()
		];
	}

	public function validateUpdatedUserData(array $newUserData): bool|array
	{
		$this->_vValidator = new vValidator($newUserData);
		$this->_vValidator->rules([
				'required' => [
					['worker_id'],
					['phone_number'],
					['company_id']
				]
			]
		);

		if($this->_vValidator->validate()) return true;

		return [
			'status' => 202,
			'message' => 'Accepted',
			'description' => $this->_vValidator->errors()
		];
	}
	#endregion

	#region CompanyValidation
	public function validateNewCompany(array $newCompanyData): bool|array
	{
		$this->_vValidator = new vValidator($newCompanyData);
		$this->_vValidator->rules([
			'required' => [
				 ['company_name'],
				 ['company_mail'],
				 ['company_state'],
				 ['company_address'],

				'email' => [
					['company_mail']
				]
			]
		]);

		if($this->_vValidator->validate()) return true;

		return [
			'status' => 202,
			'message' => 'Accepted',
			'description' => $this->_vValidator->errors()
		];
	}
	#endregion

	#region AdminValidation
	public function validateAdminCredentials(array $credentials): bool|array
	{
		$this->_vValidator = new vValidator($credentials);
		$this->_vValidator->rules([
				'required' => [
					['email'],
					['password']
				]
			]
		);

		if($this->_vValidator->validate()) return true;

		return [
			'status' => 202,
			'message' => 'Accepted',
			'description' => $this->_vValidator->errors()
		];
	}
	#endregion
}