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

	public function LogAccess(): array
	{
		$mobileDetect = $this->_mobileDetect;
		return [
			'user-agent' => $mobileDetect->getUserAgent(),
			'isMobile' => $mobileDetect->isMobile(),
			'isTablet' => $mobileDetect->isTablet(),
			'dateAccessed' => date("Y-m-d H:i:s", time())
		];
	}
}