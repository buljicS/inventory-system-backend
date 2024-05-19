<?php

declare(strict_types=1);

namespace Services;

use Kreait\Firebase\Factory as FirebaseFactory;

class FirebaseStorageServices
{
	private readonly FirebaseFactory $firebaseFactory;
	public function __construct(FirebaseFactory $firebaseFactory)
	{
		$this->firebaseFactory = $firebaseFactory
			->withServiceAccount(__DIR__ . '/../../firebase.json');
	}
}