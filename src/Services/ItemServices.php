<?php

namespace Services;

use Repositories\ItemRepository as ItemRepository;

class ItemServices
{
	private readonly ItemRepository $itemRepository;
	public function __construct(ItemRepository $itemRepository)
	{
		$this->itemRepository = $itemRepository;
	}

	public function getItemsByRoom(int $room_id): ?array
	{
		return $this->itemRepository->getItemsByRoom($room_id);
	}
}