<?php

namespace Shevsky\Ecom\Services\Tarifficator;

use Shevsky\Ecom\Util\ILogger;
use Shevsky\Ecom\Util\AbstractLogger;

class TarifficatorLogger extends AbstractLogger implements ILogger
{
	const FILE = 'ecom.tarifficator.log';

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
