<?php

namespace Services;

use Repositories\ItemsRepository as ItemsRepository;
use Utilities\ValidatorUtility as Validator;

class ItemsServices
{
	private readonly ItemsRepository $itemRepository;
	private readonly Validator $validator;

	public function __construct(ItemsRepository $itemRepository, Validator $validator)
	{
		$this->itemRepository = $itemRepository;
		$this->validator = $validator;
	}

	public function getItemsByRoom(int $room_id): ?array
	{
		return $this->itemRepository->getItemsByRoom($room_id);
	}

	public function createNewItems(array $newItems): array
	{
		//validate new item data
		$areNewItemsValid = $this->validator->validateNewItems($newItems);
		if($areNewItemsValid !== true) return $areNewItemsValid;

		//after validataion separate options and item
		$options = $newItems['generate_options'];
		$item = $newItems['item'];

		//go through all possible cases
		switch (true) {
			case $options['batch_generate'] == false && $options['with_qrcodes'] == false :
				//generateItem
				//insertItem into DB
				break;

			case $options['batch_generate'] == false && $options['with_qrcodes'] == true :
				//generateItem
				//generateQRCode
				//intert into DB
				break;

			case $options['batch_generate'] == true && $options['with_qrcodes'] == false :
				//generateMultipleItems
				//intert them into DB
				break;

			case $options['batch_generate'] == true && $options['with_qrcodes'] == true :
				//generateMultipleItems
				//generateQRCode for each item
				//save to DB
				break;
		}

		return [
			'status' => 500,
			'message' => 'Internal Server Error',
			'description' => 'Error while processing your request, please try again later',
		];
	}

	public function updateItem(array $updatedItem): array
	{
		$isUpdateItemValid = $this->validator->validateUpdateItem($updatedItem);
		if($isUpdateItemValid !== true) return $isUpdateItemValid;

		$isItemUpdated = $this->itemRepository->updateItem($updatedItem);
		if($isItemUpdated)
			return [
				'status' => 200,
				'message' => 'Success',
				'description' => 'Item updated successfully'
			];

		return [
			'status' => 404,
			'message' => 'Not found',
			'description' => 'Item not found'
		];
	}

	public function deleteItem(int $item_id): array
	{
		$isItemDeleted = null;
		$isItemInActiveInventoryProcess = $this->itemRepository->checkIfItemIsActive($item_id);
		if($isItemInActiveInventoryProcess)
			return [
				'status' => 400,
				'message' => 'Forbidden',
				'description' => 'This item is in active inventory process or it does not exist'
			];
		else
			$isItemDeleted = $this->itemRepository->deleteItem($item_id);


		if($isItemDeleted)
			return [
				'status' => 200,
				'message' => 'Success',
				'description' => 'Item deleted successfully'
			];

		return [
			'status' => 500,
			'message' => 'Internal Server Error',
			'description' => 'Error while deleting item, please try again later'
		];

	}
}