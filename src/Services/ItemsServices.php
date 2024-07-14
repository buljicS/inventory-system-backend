<?php

namespace Services;

use Repositories\ItemsRepository as ItemRepository;

class ItemsServices
{
	private readonly ItemsRepository $itemRepository;
	public function __construct(ItemsRepository $itemRepository)
	{
		$this->itemRepository = $itemRepository;
	}

	public function getItemsByRoom(int $room_id): ?array
	{
		return $this->itemRepository->getItemsByRoom($room_id);
	}

	public function createNewItems(array $requestBody)
	{
	}
}