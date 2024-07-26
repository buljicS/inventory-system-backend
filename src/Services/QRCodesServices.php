<?php

namespace Services;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer as QRCodeWriter;
use BaconQrCode\Renderer\ImageRenderer as QRCodeRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd as SvgImageBackEnd;

use Services\FirebaseServices as FirebaseServices;

class QRCodesServices
{
	private readonly FirebaseServices $firebaseServices;
	public function __construct(FirebaseServices $firebaseServices)
	{
		$this->firebaseServices = $firebaseServices;
	}

	public function generateQRCode(): string
	{
		$startTime = microtime(true);
		$renderer = new QRCodeRenderer(
			new RendererStyle(300, 3),
			new SvgImageBackEnd()
		);
		$writer = new QRCodeWriter($renderer);
		$uploadOptions = [
			"file-type" => 2,
			"dir" => 'qrCodes/',
			"mime-type" => "application/svg+xml",
    		"predefinedAcl" => "PUBLICREAD"
		];

		for($i = 0; $i < 25; $i++) {
			$fileName = 'test-normal-code' . $i . '.svg';
			$writer->writeFile("[roomId => 1, itemId = 1, name => 'some text', lastScanned => '26.07.2024']", $_ENV['LOCAL_STORAGE_URL'] . 'tmp/' . $fileName);
			$decodedFile = file_get_contents($_ENV['LOCAL_STORAGE_URL'] . 'tmp/' . $fileName);
			$uploadOptions["name"] = $fileName;
			$this->firebaseServices->uploadFile($decodedFile, $uploadOptions);
		}
		$endTime = microtime(true);

		return 'Done in ' . round($endTime - $startTime, 4) . 's';
	}

}