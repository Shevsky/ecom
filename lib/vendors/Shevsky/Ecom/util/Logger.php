<?php

namespace Shevsky\Ecom\Util;

class Logger
{
	/**
	 * @return bool
	 */
	private static function isDebug()
	{
		return !!\waSystemConfig::isDebug();
	}

	/**
	 * @param string $file
	 * @param string $message
	 * @param array $info
	 */
	public static function debug($file, $message, array $info = [])
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
	 * @param string $file
	 * @param string $message
	 * @param array $info
	 */
	public static function error($file, $message, array $info = [])
	{
		\waLog::log($message, $file);
		if (!empty($info))
		{
			\waLog::dump($info, $file);
		}
	}
}
