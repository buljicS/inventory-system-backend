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

	public function getAllCompanies(): array
	{
		return $this->companiesRepository->GetAllCompaniesForAdmin();
	}

	public function updateCompany(int $companyId, array $newCompanyData) {}
}