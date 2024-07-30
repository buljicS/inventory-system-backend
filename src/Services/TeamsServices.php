<?php

namespace Services;
use Repositories\TeamsRepository as TeamsRepository;
use Utilities\ValidatorUtility as Validator;

class TeamsServices
{
	private readonly TeamsRepository $teamsRepository;
	private readonly Validator $validator;

	public function __construct(TeamsRepository $teamsRepository, Validator $validator)
	{
		$this->teamsRepository = $teamsRepository;
		$this->validator = $validator;
	}

	public function getAllTeams(int $company_id): array
	{
		return $this->teamsRepository->getAllTeams($company_id);
	}

	public function getTeamMembers(int $team_id): array
	{
		return $this->teamsRepository->getTeamMembers($team_id);
	}

	public function getActiveWorkers(int $company_id): array
	{
		return $this->teamsRepository->getActiveWorkers($company_id);
	}

	public function createNewTeam(array $newTeam): array
	{
		$isNewTeamValid = $this->validator->validateNewTeam($newTeam);
		if ($isNewTeamValid !== true) return $isNewTeamValid;

		return $this->teamsRepository->createNewTeam($newTeam);
	}
}