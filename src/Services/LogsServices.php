<?php

namespace Services;

use Detection\Exception\MobileDetectException;
use Detection\MobileDetect as MobileDetect;
use Repositories\LogsRepository as LogsRepository;

class LogsServices
{
	private MobileDetect $_mobileDetect;
	private LogsRepository $_logRepository;

	public function __construct(MobileDetect $mobileDetect, LogsRepository $logRepository)
	{
		$this->_logRepository = $logRepository;
		$this->_mobileDetect = $mobileDetect;
	}

	public function GetAllLogs(): array {
		return $this->_logRepository->getAllLogs();
	}

	/**
	 * @throws MobileDetectException
	 */
	public function logAccess(int $isLoggedInSuccessfully, ?int $workerId, ?string $note): array
	{
		$accessLog = [];
		$mobileDetect = $this->_mobileDetect;

		$accessLog['device_type'] = match (true) {
			$mobileDetect->isMobile() => 'phone',
			$mobileDetect->isTablet() => 'tablet',
			default => 'computer',
		};

		$accessLog['user_agent'] = $mobileDetect->getUserAgent();
		$accessLog['ip_address'] = $_SERVER["REMOTE_ADDR"];
		$accessLog['referer'] = $_SERVER["HTTP_REFERER"];
		$accessLog['date_accessed'] = date("Y-m-d H:i:s", time());
		$accessLog['worker_id'] = $workerId;
		$accessLog['is_logged_in'] = $isLoggedInSuccessfully;
		$accessLog['note'] = $note;

		$this->_logRepository->insertNewLog($accessLog);

		return $accessLog;
	}
}