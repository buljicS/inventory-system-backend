<?php

namespace Services;

use Repositories\CompaniesRepository;
use Utilities\ValidatorUtility as Validator;
use Repositories\CompaniesRepository as Companies;

class CompaniesServices
{
	private readonly CompaniesRepository $companiesRepository;
	private readonly Validator $validator;
	private readonly Companies $companies;

	public function __construct(CompaniesRepository $companiesRepository, Validator $validator, Companies $companies)
	{
		$this->companiesRepository = $companiesRepository;
		$this->validator = $validator;
		$this->companies = $companies;
	}

	public function getAllCompanies(): array
	{
		return $this->companiesRepository->GetAllCompaniesForAdmin();
	}

	public function addNewCompany(array $newCompany): array
	{
		$isNewCompanyValid = $this->validator->validateNewCompany($newCompany);
		if($isNewCompanyValid !== true) {
			return $isNewCompanyValid;
		}

		$isCompanyAdded = $this->companies->insertNewCompany($newCompany);
		if($isCompanyAdded)
			return [
				'status' => 200,
				'message' => 'Success',
				'description' => 'Company added successfully'
			];

		return [
			'status' => 500,
			'message' => 'Internal Server Error',
			'description' => 'Error while adding new company, please try again'
		];
	}

	public function updateCompany(int $companyId, array $newCompanyData) {}

}