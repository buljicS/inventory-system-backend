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

	public function updateCompany(array $newCompanyData): bool|array
	{
		$isCompanyDataValid = $this->validator->validateNewCompanyData($newCompanyData);

		if($isCompanyDataValid !== true) {
			return $isCompanyDataValid;
		}

		$isCompanyUpdated = $this->companies->updateCompany($newCompanyData);

		if($isCompanyUpdated)
			return [
				'status' => 200,
				'message' => 'Success',
				'description' => 'Company updated successfully'
			];

		return [
			'status' => 500,
			'message' => 'Internal Server Error',
			'description' => 'Error while updating company, please try again'
		];
	}

	public function deleteCompany(int $company_id): array
	{
		$isCompanyDeleted = $this->companies->deleteCompany($company_id);

		if($isCompanyDeleted)
			return [
				'status' => 200,
				'message' => 'Success',
				'description' => 'Company deleted successfully'
			];

		return [
			'status' => 500,
			'message' => 'Internal Server Error',
			'description' => 'Error while deleting company, please try again'
		];
	}

}