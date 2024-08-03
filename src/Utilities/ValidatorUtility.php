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

	#region RoomValidation
	public function validateNewRoom(array $newRoom):bool|array
	{
		$this->validator = new Validator($newRoom);
		$this->validator->rules([
			'required' => [
				['company_id'],
				['room_name'],
				['room_number'],
				['room_description']
			],
			'min' => [
				[['company_id'], 1],
				[['room_number'], 1]
			]
		]);

		if($this->validator->validate()) return true;

		return [
			'status' => 400,
			'message' => 'Bad request',
			'description' => $this->validator->errors()
		];
	}

	public function validateUpdatedRoom(array $updatedRoom): bool|array
	{
		$this->validator = new Validator($updatedRoom);
		$this->validator->rules([
			'required' => [
				['room_id'],
				['room_name'],
				['room_number'],
				['room_description'],
				['isActive']
			],
			'min' => [
				[['room_id'], 1],
				[['room_number'], 1],
				[['isActive'], 0]
			],
			'max' => [
				[['isActive'], 1]
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

	#region ItemValidation
	public function validateNewItems(array $newItems): bool|array
	{
		$this->validator = new Validator($newItems);
		$this->validator->rules([
			'required' => [
				//options
				'generate_options.item_quantity',
				'generate_options.with_qrcodes',

				//item
				'item.room_id',
				'item.country_of_origin',
				'item.serial_no',
			],
			'boolean' => [
				'generate_options.with_qrcodes'
			],
			'min' => [
				['generate_options.item_quantity', 1],
				['item.room_id', 1]
			],
			'max' => [
				['generate_options.item_quantity', 50]
			]
		]);

		if($this->validator->validate()) return true;

		return [
			'status' => 400,
			'message' => 'Bad request',
			'description' => $this->validator->errors()
		];
	}

	public function validateUpdateItem(array $updatedItem): bool|array
	{
		$this->validator = new Validator($updatedItem);
		$this->validator->rules([
			'required' => [
				['item_id'],
				['item_name'],
				['serial_no'],
				['country_of_origin'],
			],
			'min' => [
				[['item_id'], 1],
			],
			'lengthMin' => [
				[['serial_no'], 3]
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

	#region TeamValidation
	public function validateNewTeam(array $newTeamData): bool|array
	{
		$this->validator = new Validator($newTeamData);
		$this->validator->rules([
			'required' => [
				['team_name'],
				['company_id'],
				['worker_id'],
			],
			'lengthMin' => [
				[['team_name'], 3]
			],
			'min' => [
				[['company_id'], 1],
				[['worker_id'], 1]
			]
		]);

		if($this->validator->validate()) return true;
		else {
			return [
				'status' => 400,
				'message' => 'Bad request',
				'description' => $this->validator->errors()
			];
		}
	}
	#endregion

	#region TasksValidation
	public function validateNewTask(array $newTaskData): bool|array
	{
		$currentDate = date("Y-m-d", time());
		$this->validator = new Validator($newTaskData);
		$this->validator->rules([
			'required' => [
				['team_id'],
				['room_id'],
				['start_date'],
				['worker_id']
			],
			'min' => [
				[['team_id'], 1],
				[['room_id'], 1],
				[['worker_id'], 1]
			],
			'dateAfter' => [
				['start_date', $currentDate]
			]
		]);

		if($this->validator->validate()) return true;

		else {
			return [
				'status' => 400,
				'message' => 'Bad request',
				'description' => $this->validator->errors()
			];
		}
	}

	public function validateTaskResponse(array $taskResponse): array|bool
	{
		$this->validator = new Validator($taskResponse);

		$this->validator->rules([
			'required' => [
				['task_id'],
				['task_summary'],
				['status']
			],
			'min' => [
				[['task_id'], 1]
			],
			'lengthMin' => [
				[['task_summary'], 3]
			]
		]);

		if($this->validator->validate()) return true;

		else {
			return [
				'status' => 400,
				'message' => 'Bad request',
				'description' => $this->validator->errors()
			];
		}
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