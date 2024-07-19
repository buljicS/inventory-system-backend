<?php

declare(strict_types=1);

namespace Services;

use DI\Container;
use Utilities\FirebaseUtility as FirebaseUtility;

class FirebaseServices
{
	private readonly Container $container;
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	public function getFirebaseInstance(): mixed {
		$firebaseInstance = $this->container->get(FirebaseUtility::class);
		return $firebaseInstance->getStorageBucket();
	}
}