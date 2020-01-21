<?php

namespace Shevsky\Ecom\Services\Tarifficator;

use Shevsky\Ecom\Util\Logger;

class TarifficatorLogger
{
	const FILE = 'ecom.tarifficator.log';

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
