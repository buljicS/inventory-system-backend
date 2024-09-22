<?php

namespace Unit;
use PHPUnit\Framework\TestCase;
use Utilities\TokenUtility;

class TokenUtilityTest extends TestCase
{
	private $tokenUtility;
	protected function setUp(): void
	{
		parent::setUp();

		$this->tokenUtility = new TokenUtility();
	}
	public function testTokenOnDifferentByteLength(): void
	{
		$byteLengths = [2, 4, 6, 8, 16, 32, 64, 128];
		for($x = 0; $x < count($byteLengths); $x++)
		{
			$token = $this->tokenUtility->GenerateBasicToken($byteLengths[$x]);
			$this->assertEquals($byteLengths[$x] * 2, strlen($token));
		}
	}
	public function testGenerateToken()
	{
		$ranBytes = 16;

		$token = $this->tokenUtility->GenerateBasicToken($ranBytes);

		$this->assertIsString($token);
		$this->assertEquals($ranBytes * 2, strlen($token));
	}
}