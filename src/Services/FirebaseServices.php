<?php

declare(strict_types=1);

namespace Services;

use DI\Container;
use Utilities\FirebaseUtility as FirebaseUtility;
use Utilities\TokenUtility as TokenUtility;
use Repositories\FirebaseRepository as FirebaseRepository;
use Utilities\HelperUtility as Helper;

class FirebaseServices
{
	private readonly Container $container;
	private readonly TokenUtility $tokenUtility;
	private readonly FirebaseRepository $firebaseRepository;
	private readonly Helper $helper;

	public function __construct(Container $container, TokenUtility $tokenUtility, FirebaseRepository $firebaseRepository, Helper $helper)
	{
		$this->tokenUtility = $tokenUtility;
		$this->container = $container;
		$this->firebaseRepository = $firebaseRepository;
		$this->helper = $helper;
	}

	public function getFirebaseInstance(): mixed {
		$firebaseInstance = $this->container->get(FirebaseUtility::class);
		return $firebaseInstance->getStorageBucket();
	}

	public function uploadFile(string $fileToUpload, array $fileOptions): array
	{
		//upload file
		$storage = $this->getFirebaseInstance();
		$storage->upload($fileToUpload, [
			'name' => $fileOptions['dir'] . $fileOptions['name'],
			'type' => $fileOptions['mime-type'],
			'predefinedAcl' => $fileOptions['predefinedAcl'],
		]);

		//save uploaded file to database
		$fileOptions['file_path'] = $_ENV['BUCKET_URL'] . $fileOptions['dir'] . $fileOptions['name'];
		$this->firebaseRepository->saveFile($fileOptions);

		return [
			'status' => 200,
			'message' => 'Success',
			'description' => 'File uploaded successfully',
			'file' => [
				'url' => $fileOptions['file_path'],
				'filename' => $fileOptions['name']
			]
		];
	}

	public function getAllFilesByDir(string $requestedDir): array
	{
		$reqDirPath = $this->helper->normailzePath($requestedDir);
		$storage = $this->getFirebaseInstance();
		$obj = $storage->objects([
			'prefix' => $reqDirPath,
			'delimiter' => '/'
		]);

		$objArray = iterator_to_array($obj);
		if(count($objArray) != 0) {
			for ($i = 1; $i < count($objArray); $i++) {
				$meta = $objArray[$i]->info();
				$files[] = [
					'url' => $_ENV['BUCKET_URL'] . $objArray[$i]->name(),
					'size' => $meta['size'],
					'type' => $meta['contentType'],
				];
			}
		}

		return [
			$reqDirPath => $files ?? []
		];
	}

	public function getFileByName(string $requestedDir, string $fileName): array
	{
		$reqDirPath = $this->helper->normailzePath($requestedDir);
		$storage = $this->getFirebaseInstance();
		$obj = $storage->objects([
			'prefix' => $reqDirPath . $fileName,
			'delimiter' => '/'
		]);

		$objArray = iterator_to_array($obj);
		$meta = $objArray[0]->info();
		$file[] = [
			'url' => $_ENV['BUCKET_URL'] . $objArray[0]->name(),
			'size' => $meta['size'],
			'type' => $meta['contentType'],
		];
		return [
			'file' => $file ?? []
		];
	}

	public function deleteFileFromStorage(string $dir, string $fileName): array
	{
		$reqDirPath = $this->helper->normailzePath($dir);
		$storage = $this->getFirebaseInstance();
		$objToDelete = $storage->object($reqDirPath . $fileName);
		try {
			$objToDelete->delete();
		} catch (\Exception $e) {
			$error = json_decode($e->getMessage());
			if($error->{'error'}->{'code'} == 404) //accessing stdClass values $decoded->{'prop'}
				return [
					'status' => 400,
					'message' => 'Not found',
					'description' => 'File not found'
				];
			else
				return [
					'status' => $error->{'error'}->{'code'},
					'message' => 'Firebase error',
					'description' => $error->{'error'}->{'message'}
				];
		}

		return [
			'status' => 200,
			'message' => 'Success',
			'description' => 'File deleted'
		];
	}
}