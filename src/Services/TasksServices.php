<?php

namespace Services;

use Repositories\TaksRepository as TasksRepository;
use Utilities\ValidatorUtility as Validator;

class TasksServices
{
	private readonly TasksRepository $tasksRepository;
	private readonly Validator $validator;

	public function __construct(TasksRepository $tasksRepository, Validator $validator)
	{
		$this->tasksRepository = $tasksRepository;
		$this->validator = $validator;
	}

	public function addTask(array $newTask): array
	{
		$isNewTaskValid = $this->validator->validateNewTask($newTask);
		if ($isNewTaskValid !== true) return $isNewTaskValid;

		$isAdded = $this->tasksRepository->insertNewTask($newTask);
		if($isAdded)
			return [
				'status' => 202,
				'message' => 'Created',
				'description' => 'Task added successfully'
			];

		return [
			'status' => 500,
			'message' => 'Internal Server Error',
			'description' => 'Error while adding task, please try again'
		];
	}

	public function getAllTasksByRoom(int $room_id)
	{
		return $this->tasksRepository->getAllTasksByRoom($room_id);
	}

	public function taskCurrentStatus(int $task_id)
	{

	}

	public function endTask(array $reqBody)
	{

	}
}