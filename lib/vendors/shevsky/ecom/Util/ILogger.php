<?php

namespace Shevsky\Ecom\Util;

interface ILogger
{
	/**
	 * @param string $message
	 * @param array $info
	 * @param string $file
	 */
	public function debug($message, array $info = [], $file = 'ecom.log');

	/**
	 * @param string $message
	 * @param array $info
	 * @param string $file
	 */
	public function details($message, array $info = [], $file = 'ecom.log');

	/**
	 * @param string $message
	 * @param array $info
	 * @param string $file
	 */
	public function error($message, array $info = [], $file = 'ecom.log');
}
