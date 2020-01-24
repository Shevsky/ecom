<?php

namespace Shevsky\Ecom\Plugin;

use Shevsky\Ecom\Util\Logger;

class CalculatorLogger
{
	const FILE = 'ecom.calculator.log';

	/**
	 * @param string $message
	 * @param array $info
	 */
	public static function debug($message, array $info = [])
	{
		Logger::debug(self::FILE, $message, $info);
	}

	/**
	 * @param string $message
	 * @param array $info
	 */
	public static function error($message, array $info = [])
	{
		Logger::error(self::FILE, $message, $info);
	}
}

