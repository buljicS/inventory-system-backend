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

}