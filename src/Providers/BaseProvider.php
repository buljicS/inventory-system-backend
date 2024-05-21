<?php

namespace Providers;

abstract class BaseProvider
{
	abstract public function register();
	abstract public function boot();


}