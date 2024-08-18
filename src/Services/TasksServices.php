<?php

namespace Services;

use Repositories\TaksRepository as TasksRepository;
use Repositories\ItemsRepository as ItemsRepository;
use Utilities\ValidatorUtility as Validator;


class TasksServices
{
	private readonly TasksRepository $tasksRepository;
	private readonly Validator $validator;
	private readonly ItemsRepository $itemsRepository;

	public function __construct(TasksRepository $tasksRepository, Validator $validator, ItemsRepository $itemsRepository)
	{
		$this->tasksRepository = $tasksRepository;
		$this->validator = $validator;
		$this->itemsRepository = $itemsRepository;
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

	public function getAllTasksByCompany(int $company_id): array
	{
		return $this->tasksRepository->getAllTasksByCompany($company_id);
	}

	public function taskCurrentStatus(int $task_id): array
	{
		$respArr = [];

		//get total items count for given task
		$room_id = $this->tasksRepository->getRoomByTask($task_id);
		$itemsInRoom = $this->itemsRepository->getItemsByRoom($room_id);
		$itemsCount = count($itemsInRoom);
		$respArr['total_items'] = $itemsCount;

		//get currently scanned items for given task
		$scannedItems = $this->tasksRepository->getScannedItemsForTask($task_id);
		$scannedItemsCount = count($scannedItems);
		$respArr['currently_scanned'] = $scannedItemsCount;

		//completion [%]
		$respArr['completed'] = round(($scannedItemsCount / $itemsCount) * 100 , 0) . "%";

		//start_date
		$respArr['start_date'] = $this->tasksRepository->getTaskById($task_id)['start_date'];

		//scanned items
		$scItems = $this->tasksRepository->getScannedItems($task_id);
		for($i = 0; $i < count($scItems); $i++) {
			$respArr['scanned_items'][$i] = $scItems[$i];
		}

		return $respArr;

	}

	public function endTask(array $taskResponse): array
	{
		$isTaskResponseValid = $this->validator->validateTaskResponse($taskResponse);
		if ($isTaskResponseValid !== true) return $isTaskResponseValid;

		$isAdded = $this->tasksRepository->insertTaskResponse($taskResponse);
		if($isAdded)
			return [
				'status' => 202,
				'message' => 'Created',
				'description' => 'Task closed successfully'
			];

		return [
			'status' => 500,
			'message' => 'Internal Server Error',
			'description' => 'Error while adding task, please try again'
		];
	}

	public function getAllTasksForWorker(int $worker_id): array
	{
		return $this->tasksRepository->getAllTasksForWorker($worker_id);
	}

	public function archiveTask(array $endedTask): array
	{
		$archiveReport = $this->tasksRepository->generateArchiveRecord($endedTask['task_id']);
		if(!empty($archiveReport)) {

			for($i = 0; $i < count($archiveReport); $i++) {
				$archiveReport[$i] = [
					'room_name' => $endedTask['room_name'],
					'item_name' => $archiveReport[$i]['item_name'],
					'team_name' => $endedTask['team_name'],
					'date_scanned' => $archiveReport[$i]['date_scanned'],
					'note' => $archiveReport[$i]['note'],
					'additional_picture' => $archiveReport[$i]['additional_picture'],
					'worker_id' => $archiveReport[$i]['worker_id'],
					'worker_full_name' => $archiveReport[$i]['worker_full_name'],
					'worker_email' => $archiveReport[$i]['worker_email'],
					'phone_number' => $archiveReport[$i]['phone_number'],
					'archived_by' => $endedTask['worker_id'],
					'task_id' => $endedTask['task_id']
				];
			}

			$isInserted = $this->tasksRepository->saveToArchive($archiveReport);

			if($isInserted === true)
				return [
					'status' => 202,
					'message' => 'Created',
					'description' => 'Task archived successfully'
				];
		}

		return [
			'status' => 404,
			'message' => 'Not found',
			'description' => 'Task not found or already archived'
		];
	}

	public function getArchivedTasksByUser(int $worker_id, string $role): array
	{
		return $this->tasksRepository->getArchivedTasksByUser($worker_id, $role);
	}
}