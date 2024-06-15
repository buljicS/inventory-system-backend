<?php

namespace Services;

use Repositories\CompaniesRepository;

class CompaniesServices
{
	private readonly CompaniesRepository $companiesRepository;

	public function __construct(CompaniesRepository $companiesRepository)
	{
		$this->companiesRepository = $companiesRepository;
	}

	public function GetAllCompanies(): array
	{
		return $this->companiesRepository->GetAllCompaniesForAdmin();
	}
}