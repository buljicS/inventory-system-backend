<?php

declare(strict_types=1);

namespace Services;

use Kreait\Firebase\Factory as FirebaseFactory;
use Kreait\Firebase\Storage as FirebaseStorage;

class FirebaseStorageServices
{
	private readonly FirebaseFactory $firebaseFactory;
	private FirebaseStorage $storage;

	public function __construct(FirebaseFactory $firebaseFactory)
	{
		$this->firebaseFactory = $firebaseFactory
			->withServiceAccount(__DIR__ . '/../../firebase.json');

		$this->storage = $this->firebaseFactory->createStorage();
	}

	public function GetAllFilesByDir(string $dir): string
	{
		$storage = $this->storage->getBucket();
		$storage->upload($file);
	}
}