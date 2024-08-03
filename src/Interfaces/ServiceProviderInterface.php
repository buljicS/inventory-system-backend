<?php

namespace Interfaces;

use Psr\Container\ContainerInterface;

interface ServiceProviderInterface
{
	public static function register(ContainerInterface $container): void;
	public static function boot();
}