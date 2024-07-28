<?php

namespace Services;

use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer as QRCodeWriter;
use BaconQrCode\Renderer\ImageRenderer as QRCodeRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd as SvgImageBackEnd;

use Services\FirebaseServices as FirebaseServices;
use Utilities\ValidatorUtility as ValidatorUtility;

class QRCodesServices
{
	private readonly FirebaseServices $firebaseServices;
	private readonly ValidatorUtility $validatorUtility;

	public function __construct(FirebaseServices $firebaseServices, ValidatorUtility $validatorUtility)
	{
		$this->firebaseServices = $firebaseServices;
		$this->validatorUtility = $validatorUtility;
	}

	public function generateQRCode(array $qrCodes): array|string
	{
		$options = $qrCodes['qrcode_options'];
		$qrcodes_data = $qrCodes['qrcode_data'];

		if($options["amount"] > count($qrcodes_data))
			return [
				'status' => 400,
				'message' => 'Bad request',
				'description' => 'Amount of qr codes need to match desired amount for generation'
			];

		//qr code generator init
		$renderer = new QRCodeRenderer(
			new RendererStyle(300, 3),
			new SvgImageBackEnd()
		);

		$writer = new QRCodeWriter($renderer);

		//upload options for firebase
		$uploadOptions = [
			"file-type" => 2,
			"dir" => 'qrCodes/' . $options['saveToDir'],
			"mime-type" => "application/svg+xml",
    		"predefinedAcl" => "PUBLICREAD"
		];

		$newQRCodes = [];

		if (!file_exists('tmp'))
			mkdir('tmp', 755);

		for($i = 0; $i < $options['amount']; $i++) {
			//generate qrCode data
			$fileName = $qrcodes_data[$i]['item_name'] . '-QRCode' . '.svg';
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

		return [
			'status' => 202,
			'message' => 'Created',
			'description' => 'QRCodes created',
			'newQRCodes' => $newQRCodes
		];
	}

}