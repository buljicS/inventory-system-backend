<?php

namespace Services;

use Repositories\RoomRepository as RoomRepo;
use Utilities\ValidatorUtility as Validator;

class RoomServices
{
	private readonly RoomRepo $roomRepo;
	private readonly Validator $validator;

	public function __construct(RoomRepo $roomRepo, Validator $validator)
	{
		$this->roomRepo = $roomRepo;
		$this->validator = $validator;
	}

	public function addNewRoom(array $newRoom): array
	{
		$isNewRoomValid = $this->validator->validateNewRoom($newRoom);
		if($isNewRoomValid !== true) {
			return $isNewRoomValid;
		}

		$isRoomAdded = $this->roomRepo->insertNewRoom($newRoom);

		if($isRoomAdded)
			return [
				'status' => 200,
				'message' => 'Success',
				'description' => 'Room added successfully'
			];

		return [
			'status' => 500,
			'message' => 'Internal Server Error',
			'description' => 'Error while adding new room, please try again'
		];
	}

	public function getAllRooms(): ?array
	{
		return $this->roomRepo->getAllRooms();
	}

	public function getAllRoomsByCompanyId(int $company_id): ?array
	{
		return $this->roomRepo->getRoomByCompanyId($company_id);
	}

	public function deleteRoom(int $room_id): array
	{
		return ['ok'];
	}
}