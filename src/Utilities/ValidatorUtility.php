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

	public function validateRegisterUserInput(array $newUserInput): array
	{

	}

	public function validateLoginUserInput(array $userInput): array
	{

	}

}