<?php

declare(strict_types=1);

namespace Utilities;

use Google\Cloud\Storage\Bucket;
use Kreait\Firebase\Factory as FirebaseFactory;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Storage;

class FirebaseUtility
{
	private readonly FirebaseFactory $firebaseFactory;
	private readonly Storage $storage;

	public function __construct(FirebaseFactory $firebaseFactory)
	{
		$this->firebaseFactory = $firebaseFactory->withServiceAccount(__DIR__ . '/../../firebase.json');
		$this->storage = $this->firebaseFactory->createStorage();
	}

	public function getStorageBucket(): Bucket
	{
		return $this->storage->getBucket();
	}
}