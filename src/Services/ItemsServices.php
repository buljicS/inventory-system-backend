<?php

namespace Services;

use Repositories\ItemsRepository as ItemsRepository;
use Utilities\ValidatorUtility as Validator;
use Services\QRCodesServices as QRCodesServices;
use Repositories\RoomsRepository as RoomsRepository;
use Repositories\QRCodesRepository as QRCodesRepository;

class ItemsServices
{
	private readonly ItemsRepository $itemRepository;
	private readonly Validator $validator;
	private readonly QRCodesServices $qrcodesServices;
	private readonly RoomsRepository $roomsRepository;
	private readonly QRCodesRepository $qrCodesRepository;

	public function __construct(ItemsRepository $itemRepository,
								Validator $validator,
								QRCodesServices $qrcodesServices,
								RoomsRepository $roomsRepository,
								QRCodesRepository $qrCodesRepository)
	{
		$this->itemRepository = $itemRepository;
		$this->validator = $validator;
		$this->qrcodesServices = $qrcodesServices;
		$this->roomsRepository = $roomsRepository;
		$this->qrCodesRepository = $qrCodesRepository;
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

		//after validation separate options and item
		$options = $newItems['generate_options'];
		$item = $newItems['item'];

		switch(true) {
			case $options['item_quantity'] == 1:
				$newItem[] = [
					$item['item_name'],
					$item['serial_no'],
					$item['country_of_origin'],
					$item['room_id'],
					$item['with_qrcode'] = $options['with_qrcodes']
				];
				$qrcode_data = $this->itemRepository->insertNewItems($newItem, 1);
				if($options['with_qrcodes']) {
					$qrcode['qrcode_data'] = $qrcode_data;
					$qrcode['qrcode_options'] = [
						'saveToDir' => $this->roomsRepository->getRoomName((int)$item['room_id']) . "-" . date('d-m-Y_H:i:s') . "/",
						'amount' => 1
					];
					$qrCodeGenerated = $this->qrcodesServices->generateQRCode($qrcode);
					if($qrCodeGenerated['status'] == 202) {
						$this->qrCodesRepository->insertNewQRCodes($qrCodeGenerated['newQRCodes']);
						return [
							'status' => 200,
							'message' => 'Success',
							'description' => 'Items and qr codes generated successfully.'
						];
					}
					else
						return $qrCodeGenerated;
				}
				return [
					'status' => 200,
					'message' => 'Success',
					'description' => 'Item created successfully.'
				];

			case $options['item_quantity'] > 1:
				$items = [];
				for($i = 0; $i < $options['item_quantity']; $i++) {
					$items[] = [
						$item['item_name'] = $options['name_pattern'] . $i,
						$item['serial_no'] => $item['serial_no'] . $i,
						$item['country_of_origin'],
						$item['room_id'],
						$item['with_qrcode'] = $options['with_qrcodes'],
					];
				}
				//watch for naming conventions because here comes integration with another service
				$qrcode_data = $this->itemRepository->insertNewItems($items, $options['item_quantity']);
				if($options['with_qrcodes']) {
					$qrcodes['qrcode_data'] = $qrcode_data;
					$qrcodes['qrcode_options'] = [
						'saveToDir' => $this->roomsRepository->getRoomName((int)$item['room_id']) . "-" . date('d-m-Y_H:i:s') . "/",
						'amount' => $options['item_quantity']
					];
					$qrCodesGenerated = $this->qrcodesServices->generateQRCode($qrcodes);
					if($qrCodesGenerated['status'] == 202)
					{
						//save qr codes to db
						$this->qrCodesRepository->insertNewQRCodes($qrCodesGenerated['newQRCodes']);
						return [
							'status' => 200,
							'message' => 'Success',
							'description' => 'Items and qr codes generated successfully.'
						];
					}

					else
						return $qrCodesGenerated;
				}
				return [
					'status' => 200,
					'message' => 'Success',
					'description' => 'Items created successfully.'
				];
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