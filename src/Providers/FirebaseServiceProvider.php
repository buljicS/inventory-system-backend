<?php

namespace Providers;

use Interfaces\ServiceProviderInterface;
use Psr\Container\ContainerInterface;
use Kreait\Firebase\Factory as FirebaseFactory;
use Utilities\FirebaseUtility as FirebaseUtility;

class FirebaseServiceProvider implements ServiceProviderInterface
{
	public static function register(ContainerInterface $container): void
	{
		$container->set(FirebaseUtility::class, function (ContainerInterface $container) {
			$firebaseFactory = new FirebaseFactory();
			return new FirebaseUtility($firebaseFactory);
		});
	}

	public static function boot() { /* Use after registration */ }
}