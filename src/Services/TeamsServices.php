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

	public function addTeamMembers(array $teamMembers): array
	{
		$newTeamMembers = $teamMembers['workers_ids'];
		$newTeamMembersCount = count($teamMembers['workers_ids']);
		if ($newTeamMembersCount > 5)
			return [
				'status' => 400,
				'message' => 'Forbidden',
				'description' => 'One team can have up to 5 members'
			];

		$currentTeam = $this->teamsRepository->getTeamMembers($teamMembers['team_id']);
		$currentTeamMembersCount = count($currentTeam);

		if (($newTeamMembersCount + $currentTeamMembersCount) > 5)
			return [
				'status' => 400,
				'message' => 'Forbidden',
				'description' => 'Currently team has ' . $currentTeamMembersCount . ' team members, you can add up to 5 members maximum'
			];

		return $this->teamsRepository->addNewTeamMembers($newTeamMembers, $teamMembers['team_id']);
	}

//	public function removeTeamMemberFromTeam(int $team_id, int $team_member_id): array
//	{
//		return $this->teamsRepository->removeMemberFromTeam($team_id, $team_member_id);
//	}
}