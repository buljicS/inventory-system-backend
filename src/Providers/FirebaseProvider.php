<?php

namespace Providers;

use Psr\Container\ContainerInterface;
use Kreait\Firebase\Factory as FirebaseFactory;
use Services\FirebaseStorageServices;


class FirebaseProvider extends BaseProvider
{
	public function register(ContainerInterface $container): FirebaseStorageServices
	{
		$container->set(FirebaseFactory::class, function (ContainerInterface $container) {
			$firebase = new FirebaseFactory();
			return new FirebaseStorageServices($firebase);
		});
	}

	public function boot() {}

}