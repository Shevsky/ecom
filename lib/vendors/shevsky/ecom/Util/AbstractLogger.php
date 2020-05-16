<?php

namespace Shevsky\Ecom\Util;

use Shevsky\Ecom\Enum;

abstract class AbstractLogger implements ILogger
{
	private $mode;

	/**
	 * @param string $mode
	 */
	public function __construct($mode)
	{
		$this->mode = $mode;
	}

	/**
	 * @param string $message
	 * @param array $info
	 * @param string $file
	 */
	public function debug($message, array $info = [], $file = 'ecom.log')
	{
		if (!self::isDebug())
		{
			return;
		}

		\waLog::log($message, $file);
		if (!empty($info))
		{
			\waLog::dump($info, $file);
		}
	}

	/**
	 * @param string $message
	 * @param array $info
	 * @param string $file
	 */
	public function details($message, array $info = [], $file = 'ecom.log')
	{
		if (!self::isDetails())
		{
			return;
		}

		\waLog::log($message, $file);
		if (!empty($info))
		{
			\waLog::dump($info, $file);
		}
	}

	/**
	 * @param string $message
	 * @param array $info
	 * @param string $file
	 */
	public function error($message, array $info = [], $file = 'ecom.log')
	{
		if (!self::isErrors())
		{
			return;
		}

		\waLog::log($message, $file);
		if (!empty($info))
		{
			\waLog::dump($info, $file);
		}
	}

	/**
	 * @return bool
	 */
	private function isDebug()
	{
		return in_array($this->mode, [Enum\DebugMode::DEBUG, Enum\DebugMode::DETAILS]);
	}

	/**
	 * @return bool
	 */
	private function isDetails()
	{
		return $this->mode === Enum\DebugMode::DETAILS;
	}

	/**
	 * @return bool
	 */
	private function isErrors()
	{
		return in_array($this->mode, [Enum\DebugMode::DEBUG, Enum\DebugMode::DETAILS, Enum\DebugMode::ERRORS]);
	}
}
