<?php

namespace Services;

use Repositories\TaksRepository as TasksRepository;
use Repositories\ItemsRepository as ItemsRepository;
use Repositories\TeamsRepository as TeamsRepository;
use Utilities\ValidatorUtility as Validator;
use Utilities\MailUtility as MailUtility;
use Dompdf\Dompdf as DomPDF;


class TasksServices
{
	private readonly TasksRepository $tasksRepository;
	private readonly Validator $validator;
	private readonly ItemsRepository $itemsRepository;
	private readonly TeamsRepository $teamsRepository;
	private readonly MailUtility $mailUtility;
	private readonly DomPDF $dompdf;

	public function __construct(TasksRepository $tasksRepository,
								Validator $validator,
								ItemsRepository $itemsRepository,
								TeamsRepository $teamsRepository,
								MailUtility $mailUtility,
								DomPDF $dompdf)
	{
		$this->tasksRepository = $tasksRepository;
		$this->validator = $validator;
		$this->itemsRepository = $itemsRepository;
		$this->teamsRepository = $teamsRepository;
		$this->mailUtility = $mailUtility;
		$this->dompdf = $dompdf;
	}

	public function addTask(array $newTask): array
	{
		$isNewTaskValid = $this->validator->validateNewTask($newTask);
		if ($isNewTaskValid !== true) return $isNewTaskValid;

		$isAdded = $this->tasksRepository->insertNewTask($newTask);
		if($isAdded === true) {
			$teamMembers = $this->teamsRepository->getTeamMembers($newTask['team_id']);
			for($i = 0; $i < count($teamMembers); $i++) {
				$body = file_get_contents('../templates/email/UserTodoTask.html');
				$body = str_replace('{{userName}}', $teamMembers[$i]['worker_fname'], $body);
				$body = str_replace('{{dashboardLink}}', $_ENV['MAIN_URL_FE'] . '/dashboard/tasks', $body);

				$this->mailUtility->SendEmail($body, 'You have been assigned a new task', $teamMembers[$i]['worker_email'], null);
			}
			return [
				'status' => 202,
				'message' => 'Created',
				'description' => 'Task added successfully'
			];
		}

		if(is_array($isAdded))
			return $isAdded;

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
		$respArr['completed'] = $scannedItemsCount == 0 ? "0%" : round(($scannedItemsCount / $itemsCount) * 100 , 0) . "%";

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
		$isAlreadyArchived = $this->tasksRepository->checkTask($endedTask['task_id']);
		if($isAlreadyArchived === false) {
			$archiveReport = $this->tasksRepository->generateArchiveRecord($endedTask['task_id']);
			if (!empty($archiveReport)) {

				for ($i = 0; $i < count($archiveReport); $i++) {
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
						'archived_by' => $endedTask['worker_id']
					];
				}

				$isInserted = $this->tasksRepository->saveToArchive($archiveReport, $endedTask['task_id']);

				if ($isInserted === true)
					return [
						'status' => 202,
						'message' => 'Created',
						'description' => 'Task archived successfully'
					];
			}
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

	public function notifyUsersAboutUpcomingTasks(): ?array
	{
		$incomingTasks = $this->tasksRepository->checkForIncomingTasks();
		if(!empty($incomingTasks))
		{
			for($i = 0; $i < count($incomingTasks); $i++)
			{
				$reminderTemplate = file_get_contents("../templates/email/UserTaskReminder.html");
				$reminderTemplate = str_replace("{{dashURL}}", $_ENV['MAIN_URL_FE'] . "/dashboard/tasks", $reminderTemplate);
				$reminderTemplate = str_replace("{{startDate}}", $incomingTasks[$i]['start_date'], $reminderTemplate);
				$reminderTemplate = str_replace("{{room}}", $incomingTasks[$i]['room_name'], $reminderTemplate);
				$reminderTemplate = str_replace("{{team}}", $incomingTasks[$i]['team_name'], $reminderTemplate);
				$reminderTemplate = str_replace("{{test@gmail.com}}", $incomingTasks[$i]['worker_email'], $reminderTemplate);
				for($j = 0; $j < count($incomingTasks[$i]['team_info']); $j++) {
					$reminderTemplate = str_replace("{{userName}}", $incomingTasks[$i]['team_info'][$j]['worker_fname'], $reminderTemplate);
					$this->mailUtility->SendEmail($reminderTemplate, "Check upcoming task", $incomingTasks[$i]['team_info'][$j]['worker_email'], null);
					$reminderTemplate = str_replace($incomingTasks[$i]['team_info'][$j]['worker_fname'], "{{userName}}", $reminderTemplate);
				}
			}
			return [
				'status' => 200,
				'message' => 'Success'
			];
		}
		return null;
	}

	public function generateTaskReport(array $reqBody)
	{
		//read template and replace content
		$rawTemplate = file_get_contents('../templates/reports/TaskReport.html');
		$rawTemplate = str_replace("{{ additional_picture }}", $_ENV['MAIN_URL_BE'] . "staticContent/logo.webp", $rawTemplate);
		$rawTemplate = str_replace("{{}}")
	}
}