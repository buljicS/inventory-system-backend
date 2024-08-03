<?php

namespace Services;

use Repositories\ItemsRepository as ItemsRepository;
use Utilities\TokenUtility as TokenUtility;
use Utilities\ValidatorUtility as Validator;
use Services\QRCodesServices as QRCodesServices;
use Repositories\RoomsRepository as RoomsRepository;
use Repositories\QRCodesRepository as QRCodesRepository;
use Services\FirebaseServices as FirebaseServices;

class ItemsServices
{
	private readonly ItemsRepository $itemRepository;
	private readonly Validator $validator;
	private readonly QRCodesServices $qrcodesServices;
	private readonly RoomsRepository $roomsRepository;
	private readonly QRCodesRepository $qrCodesRepository;
	private readonly FirebaseServices $firebaseServices;
	private readonly TokenUtility $tokenUtility;

	public function __construct(ItemsRepository $itemRepository,
								Validator $validator,
								QRCodesServices $qrcodesServices,
								RoomsRepository $roomsRepository,
								QRCodesRepository $qrCodesRepository,
								FirebaseServices $firebaseServices,
								TokenUtility $tokenUtility)
	{
		$this->itemRepository = $itemRepository;
		$this->validator = $validator;
		$this->qrcodesServices = $qrcodesServices;
		$this->roomsRepository = $roomsRepository;
		$this->qrCodesRepository = $qrCodesRepository;
		$this->firebaseServices = $firebaseServices;
		$this->tokenUtility = $tokenUtility;
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
					$item['room_id']
				];
				$qrcode_data = $this->itemRepository->insertNewItems($newItem, 1, (int)$item['room_id']);
				if($options['with_qrcodes']) {
					$qrcode['qrcode_data'] = $qrcode_data;
					$qrcode['qrcode_options'] = [
						'saveToDir' => $this->roomsRepository->getRoomName((int)$item['room_id']) . "/",
						'amount' => 1
					];
					$qrCodeGenerated = $this->qrcodesServices->generateQRCode($qrcode,false);
					if($qrCodeGenerated['status'] == 202) {
						$this->itemRepository->setQRCodesOnItems($qrCodeGenerated['qrCodes']);
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
						$item['room_id']
					];
				}
				//watch for naming conventions because here comes integration with another service
				$qrcode_data = $this->itemRepository->insertNewItems($items, $options['item_quantity'], (int)$item['room_id']);
				if($options['with_qrcodes']) {
					$qrcodes['qrcode_data'] = $qrcode_data;
					$qrcodes['qrcode_options'] = [
						'saveToDir' => $this->roomsRepository->getRoomName((int)$item['room_id']) . "/",
						'amount' => $options['item_quantity']
					];
					$qrCodesGenerated = $this->qrcodesServices->generateQRCode($qrcodes, false);
					if($qrCodesGenerated['status'] == 202)
					{
						$this->itemRepository->setQRCodesOnItems($qrCodesGenerated['qrCodes']);
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
		$isItemInActiveInventoryProcess = $this->itemRepository->checkIfItemIsActive($item_id);
		if($isItemInActiveInventoryProcess !== "ok")
			return [
				'status' => 400,
				'message' => 'Bad request',
				'description' => $isItemInActiveInventoryProcess
			];
		else
			$isItemDeleted = $this->itemRepository->deleteItem($item_id);


		if($isItemDeleted == "ok")
			return [
				'status' => 200,
				'message' => 'Success',
				'description' => 'Item deleted successfully'
			];
		else { //item had picture, delete it from firebase
			$urlParts = explode('/', $isItemDeleted);
			$dir = $urlParts[4] . '-' . $urlParts[5];
			$deleteStatus = $this->firebaseServices->deleteFileFromStorage($dir, $urlParts[6]);
			if($deleteStatus['status'] == 200) {
				return [
					'status' => 200,
					'message' => 'Success',
					'description' => 'Item and its qrcode are deleted successfully'
				];
			}
			else {
				return $deleteStatus;
			}
		}
	}

	public function scanItem(array $scannedItem, array $itemPictures): array
	{
		$isScannedItemValid = $this->validator->validateScannedItem($scannedItem);
		if($isScannedItemValid !== true) return $isScannedItemValid;

		if(!empty($itemPictures)) {
			if (!file_exists('tmp'))
				mkdir('tmp', 755);

			$localStoragePath = $_ENV['LOCAL_STORAGE_URL'] . 'tmp';

			$uploadedImage = $itemPictures['item_image'];
			if ($uploadedImage->getError() === UPLOAD_ERR_OK) {
				$fileExt = pathinfo($uploadedImage->getClientFileName(), PATHINFO_EXTENSION);
				$mimeType = 'image/' . $fileExt;
				$imageName = $this->tokenUtility->GenerateBasicToken(8) . "." . $fileExt;
				$fullPath = $localStoragePath . DIRECTORY_SEPARATOR . $imageName;
				$uploadedImage->moveTo($fullPath);
				$imageToUpload = file_get_contents($fullPath);

				$options = [
					'file-type' => 3,
					'dir' => 'reports/',
					'name' => $imageName,
					'mime-type' => $mimeType,
					'predefinedAcl' => 'PUBLICREAD'
				];
				$report_picture = $this->firebaseServices->uploadFile($imageToUpload, $options);
				$scannedItem['picture_id'] = $report_picture['file']['file_id'];
			}
		}
		else
			$scannedItem['picture_id'] = null;

		$isAdded = $this->itemRepository->insertScannedItem($scannedItem);
		if($isAdded)
			return [
				'status' => 200,
				'message' => 'Success',
				'description' => 'Item scanned successfully'
			];

		return [
			'status' => 500,
			'message' => 'Internal Server Error',
			'description' => 'Error while saving scanned item, please try again'
		];
	}
}