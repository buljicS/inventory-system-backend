<?php

namespace Utilities;

class HelperUtility
{
	public function __construct() {}

	public function normailzePath(string $rawPath): string {
		if(str_contains($rawPath, "-"))
			return str_replace("-", "/", $rawPath);

		return $rawPath . "/";
	}
}