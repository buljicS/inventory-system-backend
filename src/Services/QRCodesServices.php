<?php

namespace Services;

use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\ImageRenderer as QRCodeRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd as SvgImageBackEnd;
use BaconQrCode\Writer as QRCodeWriter;
use BaconQrCode\Renderer\GDLibRenderer as GDLibRenderer;

use Services\FirebaseServices as FirebaseServices;
use Utilities\ValidatorUtility as ValidatorUtility;
use Repositories\ItemsRepository as ItemsRepository;

class QRCodesServices
{
	private readonly FirebaseServices $firebaseServices;
	private readonly ValidatorUtility $validatorUtility;
	private readonly ItemsRepository $itemsRepository;

	public function __construct(FirebaseServices $firebaseServices, ValidatorUtility $validatorUtility, ItemsRepository $itemsRepository)
	{
		$this->firebaseServices = $firebaseServices;
		$this->validatorUtility = $validatorUtility;
		$this->itemsRepository = $itemsRepository;
	}

	public function generateQRCode(array $qrCodes, bool $forSingleItem): array|string
	{
		$isForSingleItem = $forSingleItem == null ? false : true;
		$options = $qrCodes['qrcode_options'];
		$qrcodes_data = $qrCodes['qrcode_data'];

		if($options["amount"] > count($qrcodes_data))
			return [
				'status' => 400,
				'message' => 'Bad request',
				'description' => 'Amount of qr codes need to match desired amount for generation'
			];

		//qr code generator init
//		$renderer = new QRCodeRenderer(
//			new RendererStyle(300, 3),
//			new SvgImageBackEnd()
//		);

		$renderer = new GDLibRenderer(300, 3, 'png', 10);

		$writer = new QRCodeWriter($renderer);

		//upload options for firebase
		$uploadOptions = [
			"file-type" => 2,
			"dir" => 'qrCodes/' . $options['saveToDir'],
			"mime-type" => "image/png",
    		"predefinedAcl" => "PUBLICREAD"
		];

		$newQRCodes = [];

		if (!file_exists('tmp'))
			mkdir('tmp', 755);

		for($i = 0; $i < $options['amount']; $i++) {
			//generate qrCode data
			$fileName = $qrcodes_data[$i]['item_name'] . '-QRCode' . '.png';
			$content = json_encode([
				'room_id' => $qrcodes_data[$i]['room_id'],
				'item_id' => $qrcodes_data[$i]['item_id'],
				'item_name' => $qrcodes_data[$i]['item_name']
			]);

			//generate qr codes
			$writer->writeFile($content, $_ENV['LOCAL_STORAGE_URL'] . 'tmp/' . $fileName);
			$decodedFile = file_get_contents($_ENV['LOCAL_STORAGE_URL'] . 'tmp/' . $fileName);

			//upload qr codes to firebase
			$uploadOptions["name"] = $fileName;
			$uploadedFile = $this->firebaseServices->uploadFile($decodedFile, $uploadOptions);

			//save new qrcode data for database
			$newQRCodes[] = [
				'file_name' => $fileName,
				'title' => $qrcodes_data[$i]['item_name'],
				'item_id' => $qrcodes_data[$i]['item_id'],
				'room_id' => $qrcodes_data[$i]['room_id'],
				'picture_id' => $uploadedFile['file']['file_id']
			];
		}

		//if request is being made for only single item, attach new qr code to that item straight away
		if($isForSingleItem) {
			$this->itemsRepository->setQRCodesOnItems($newQRCodes);
			return [
				'status' => 202,
				'message' => 'Created',
				'description' => 'QRCode for ' . $newQRCodes[0]['item_name'] . ' item successfully created'
			];
		}

		return [
			'status' => 202,
			'message' => 'Created',
			'description' => 'QRCodes created',
			'qrCodes' => $newQRCodes
		];
	}

}