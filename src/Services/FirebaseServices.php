<?php

declare(strict_types=1);

namespace Services;

use DI\Container;
use Utilities\FirebaseUtility as FirebaseUtility;
use Utilities\TokenUtility as TokenUtility;
use Repositories\FirebaseRepository as FirebaseRepository;

class FirebaseServices
{
	private readonly Container $container;
	private readonly TokenUtility $tokenUtility;
	private readonly FirebaseRepository $firebaseRepository;

	public function __construct(Container $container, TokenUtility $tokenUtility, FirebaseRepository $firebaseRepository)
	{
		$this->tokenUtility = $tokenUtility;
		$this->container = $container;
		$this->firebaseRepository = $firebaseRepository;
	}

	public function getFirebaseInstance(): mixed {
		$firebaseInstance = $this->container->get(FirebaseUtility::class);
		return $firebaseInstance->getStorageBucket();
	}

	public function uploadUserImage(array $uploadedFiles, int $worker_id): array
	{
		//set base path to local file folder and get uploaded image
		$localStoragePath = $_SERVER['DOCUMENT_ROOT'] . 'tempUploads';
		$uploadedImage = $uploadedFiles['image'];

		if ($uploadedImage->getError() === UPLOAD_ERR_OK) {
			//get basic image info
			$uploadedImageName = $uploadedImage->getClientFileName();
			$fileExt = pathinfo($uploadedImageName, PATHINFO_EXTENSION);
			$mimeType = 'image/' . $fileExt;
			$fullImagePath = $localStoragePath . DIRECTORY_SEPARATOR . $uploadedImageName;
			$encodedImageName = $this->tokenUtility->GenerateBasicToken(16) . "." . $fileExt;

			//move image to temp folder and prepare it for firebase upload
			$uploadedImage->moveTo($fullImagePath);
			$imageToUpload = file_get_contents($fullImagePath);

			//upload file options
			$imageOptions = [
				'name' => 'userPictures/' . $encodedImageName,
				'type' => $mimeType,
				'predefinedAcl' => 'PUBLICREAD'
			];

			$storage = $this->getFirebaseInstance();
			$storage->upload($imageToUpload, $imageOptions);

			//delete image from temp folder and save its data to database
			unlink($fullImagePath);
			$imageOptions['picture_path'] = $_ENV['BUCKET_URL'] . $imageOptions['name'];
			$imageOptions['picture_type'] = 1;
			$imageOptions['encoded_name'] = $encodedImageName;

			$isImageSaved = $this->firebaseRepository->saveImage($imageOptions, $worker_id);
			if ($isImageSaved)
				return [
					'status' => 202,
					'message' => 'Created',
					'description' => 'Image uploaded successfully',
					'data' => [
						'image_name' => $imageOptions['encoded_name'],
						'image_path' => $imageOptions['picture_path']
					]
				];
		}

		return [
			'status' => 400,
			'message' => 'Bad request',
			'description' => 'Image upload failed',
			'details' => $uploadedImage->getError()
		];
	}
}