<?php

namespace Services;

use Detection\MobileDetect as MobileDetect;
use Repositories\LogRepository as LogRepository;
use Valitron\Validator as vValidator;

class LogServices
{
	private MobileDetect $_mobileDetect;
	private LogRepository $_logRepository;

	public function __construct(MobileDetect $mobileDetect, LogRepository $logRepository)
	{
		$this->_logRepository = $logRepository;
		$this->_mobileDetect = $mobileDetect;
	}

	public function GetAllLogs(): array {
		return $this->_logRepository->GetAllLogs();
	}

	public function LogAccess(bool $isLoggedInSuccessfully, int $workerId): array
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

		$this->_logRepository->InsertNewLog($accessLog);

		return $accessLog;
	}
}