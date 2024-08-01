<?php

namespace Services;

use Repositories\TaksRepository as TasksRepository;

class TasksServices
{
	private readonly TasksRepository $tasksRepository;

	public function __construct(TasksRepository $tasksRepository)
	{
		$this->tasksRepository = $tasksRepository;
	}

	public function addTask(array $newTask): array
	{
		return ['ok'];
	}

	public function getAllTasksInCompany(int $company_id)
	{
		
	}

	public function getAllTasksInRoom(int $room_id)
	{

	}

	public function taskCurrentStatus(int $task_id)
	{

	}

	public function endTask(array $reqBody)
	{

	}
}