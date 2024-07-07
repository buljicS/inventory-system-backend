<?php

namespace Utilities;

use Valitron\Validator as Validator;


class ValidatorUtility
{
	private Validator $validator;

	public function __construct(Validator $validator)
	{
		$this->validator = $validator;
	}

	#region UserValidation
	public function validateNewUserData(array $newUserInput): bool|array
	{
		$this->validator = new Validator($newUserInput);
		$this->validator->rules(
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

		if ($this->validator->validate()) {
			return true;
		}

		return [
			'status' => 400,
			'message' => 'Bad request',
			'description' => $this->validator->errors()
		];

	}

	public function validateUserToBeAdded(array $newUser): bool|array
	{
		$this->validator = new Validator($newUser);
		$this->validator->rules([
			'required' => [
				['worker_fname'],
				['worker_lname'],
				['worker_email'],
				['company_id']
			],
			'email' => [
				['worker_email']
			],
			'min' => [
				[['company_id'], 1]
			]
		]);

		if ($this->validator->validate()) {
			return true;
		}

		return [
			'status' => 400,
			'message' => 'Bad request',
			'description' => $this->validator->errors()
		];
	}

	public function validateLoginUserInput(array $userInput): bool|array
	{
		$this->validator = new Validator($userInput);
		$this->validator->rules(
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

		if($this->validator->validate()) {
			return true;
		}

		return [
			'status' => 400,
			'message' => 'Bad request',
			'description' => $this->validator->errors()
		];
	}

	public function validateNewPasswordData(array $userInfo): bool|array
	{
		$this->validator = new Validator($userInfo);
		$this->validator->rules([
				'required' => [
					['old_password'],
					['new_password'],
					['worker_id']
				]
			]
		);

		if($this->validator->validate()) return true;

		return [
			'status' => 400,
			'message' => 'Bad request',
			'description' => $this->validator->errors()
		];
	}

	public function validateUpdatedUserData(array $newUserData): bool|array
	{
		$this->validator = new Validator($newUserData);
		$this->validator->rules([
				'required' => [
					['worker_id'],
					['phone_number'],
					['company_id']
				]
			]
		);

		if($this->validator->validate()) return true;

		return [
			'status' => 400,
			'message' => 'Bad request',
			'description' => $this->validator->errors()
		];
	}

	public function validateUpdateUserDataProvidedByAdmin(array $updatedUserInfo): bool|array {
		$this->validator = new Validator($updatedUserInfo);
		$this->validator->rules([
			'required' => [
				['worker_id'],
				['worker_lname'],
				['worker_fname'],
			]
		]);

		if($this->validator->validate()) return true;

		return [
			'status' => 400,
			'message' => 'Bad request',
			'description' => $this->validator->errors()
		];
	}
	#endregion

	#region CompanyValidation
	public function validateNewCompany(array $newCompanyData): bool|array
	{
		$this->validator = new Validator($newCompanyData);
		$this->validator->rules([
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

		if($this->validator->validate()) return true;

		return [
			'status' => 400,
			'message' => 'Bad request',
			'description' => $this->validator->errors()
		];
	}

	public function validateNewCompanyData(array $newCompanyData): bool|array
	{
		$this->validator = new Validator($newCompanyData);
		$this->validator->rules([
			'required' => [
				['company_id'],
				['company_name'],
				['company_mail'],
				['company_state'],
				['company_address']
			],
			'min' => [
				[['company_id'], 1]
			]
		]);

		if($this->validator->validate()) return true;

		return [
			'status' => 400,
			'message' => 'Bad request',
			'description' => $this->validator->errors()
		];
	}
	#endregion

	#region AdminValidation
	public function validateAdminCredentials(array $credentials): bool|array
	{
		$this->validator = new Validator($credentials);
		$this->validator->rules([
				'required' => [
					['email'],
					['password']
				]
			]
		);

		if($this->validator->validate()) return true;

		return [
			'status' => 400,
			'message' => 'Bad request',
			'description' => $this->validator->errors()
		];
	}
	#endregion


}