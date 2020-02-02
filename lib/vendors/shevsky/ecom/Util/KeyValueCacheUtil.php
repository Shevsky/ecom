<?php

namespace Shevsky\Ecom\Util;

class KeyValueCacheUtil extends StaticCacheUtil
{
	/**
	 * @param string $key
	 * @param string $name
	 * @return mixed
	 */
	public function getCache($key, $name)
	{
		$value = $this->readCache($key);
		if (!is_array($value))
		{
			$value = [];
		}

		if (array_key_exists($name, $value))
		{
			return $value[$name];
		}

		return null;
	}

	/**
	 * @param string $key
	 * @param string $name
	 * @param mixed $raw_value
	 * @param int|null $expiration
	 */
	public function setCache($key, $name, $raw_value, $expiration = null)
	{
		$value = $this->readCache($key);
		if (!is_array($value))
		{
			$value = [];
		}

		$value[$name] = $raw_value;

		$this->writeCache($key, $value);
	}
}
