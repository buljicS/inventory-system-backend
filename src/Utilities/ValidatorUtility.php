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

	public function validateUpdatedUserData(array $newUserData)
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
}