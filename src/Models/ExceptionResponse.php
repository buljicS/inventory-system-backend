<?php

namespace Models;

use JsonSerializable;

class ExceptionResponse implements JsonSerializable
{
	private string $exceptionType;
	private string $exceptionMessage;
	private int $exceptionCode;
	private string $inFile;
	private int $atLine;

	#region get-set
	/**
	 * @return int
	 */
	public function getAtLine(): int
	{
		return $this->atLine;
	}

	/**
	 * @param int $atLine
	 */
	public function setAtLine(int $atLine): void
	{
		$this->atLine = $atLine;
	}

	/**
	 * @return string
	 */
	public function getExceptionMessage(): string
	{
		return $this->exceptionMessage;
	}

	/**
	 * @param string $exceptionMessage
	 */
	public function setExceptionMessage(string $exceptionMessage): void
	{
		$this->exceptionMessage = $exceptionMessage;
	}

	/**
	 * @return string
	 */
	public function getExceptionType(): string
	{
		return $this->exceptionType;
	}

	/**
	 * @param string $exceptionType
	 */
	public function setExceptionType(string $exceptionType): void
	{
		$this->exceptionType = $exceptionType;
	}

	/**
	 * @return string
	 */
	public function getInfile(): string
	{
		return $this->inFile;
	}

	/**
	 * @param string $infile
	 */
	public function setInfile(string $infile): void
	{
		$this->inFile = $infile;
	}

	/**
	 * @param int $exceptionCode
	 */
	public function setExceptionCode(int $exceptionCode): void
	{
		$this->exceptionCode = $exceptionCode;
	}

	/**
	 * @return int
	 */
	public function getExceptionCode(): int
	{
		return $this->exceptionCode;
	}
	#endregion

	public function jsonSerialize(): array
	{
		return [
			'exceptionType' => $this->exceptionType,
			'exceptionCode' => $this->exceptionCode,
			'exceptionMessage' => $this->exceptionMessage,
			'inFile' => $this->inFile,
			'atLine' => $this->atLine
		];
	}
}