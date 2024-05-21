<?php

declare(strict_types=1);

namespace Services;

use Kreait\Firebase\Factory as FirebaseFactory;
use Kreait\Firebase\Storage;

class FirebaseStorageServices
{
	private readonly FirebaseFactory $firebaseFactory;
	private Storage $storage;
	public function __construct(FirebaseFactory $firebaseFactory)
	{
		$this->firebaseFactory = $firebaseFactory->withServiceAccount(__DIR__ . '/../../firebase.json');
		$this->storage = $this->firebaseFactory->createStorage();
	}

	public function getStorage(): Storage
	{
		return $this->storage;
	}
}