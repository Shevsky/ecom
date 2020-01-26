<?php

namespace Shevsky\Ecom\Plugin;

use Shevsky\Ecom\Util\ILogger;
use Shevsky\Ecom\Util\AbstractLogger;

class CalculatorLogger extends AbstractLogger implements ILogger
{
	const FILE = 'ecom.calculator.log';

	/**
	 * @param string $message
	 * @param array $info
	 * @param string $file
	 */
	public function debug($message, array $info = [], $file = self::FILE)
	{
		parent::debug($message, $info, $file);
	}

	/**
	 * @param string $message
	 * @param array $info
	 * @param string $file
	 */
	public function error($message, array $info = [], $file = self::FILE)
	{
		parent::error($message, $info, $file);
	}
}

