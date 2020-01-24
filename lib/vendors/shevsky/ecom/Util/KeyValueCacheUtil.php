<?php

namespace Shevsky\Ecom\Util;

class KeyValueCacheUtil
{
	private $base_key;
	private $cache_adapter;

	/**
	 * @param string $base_key
	 */
	public function __construct($base_key)
	{
		$this->base_key = $base_key;
	}

	/**
	 * @return \waFileCacheAdapter
	 */
	private function getCacheAdapter()
	{
		if (!isset($this->cache_adapter))
		{
			$this->cache_adapter = new \waFileCacheAdapter(
				[
					'path' => \waConfig::get('wa_path_cache') . "/ecom/{$this->base_key}",
				]
			);
		}

		return $this->cache_adapter;
	}

	/**
	 * @param string $key
	 * @return array
	 */
	public function readCache($key)
	{
		$value = $this->getCacheAdapter()->get($key);
		if (!is_array($value))
		{
			$value = [];
		}

		return $value;
	}

	/**
	 * @param string $key
	 * @param array $value
	 */
	public function writeCache($key, array $value)
	{
		$this->getCacheAdapter()->set($key, $value);
	}

	/**
	 * @param string $key
	 * @param string $name
	 * @return mixed
	 */
	public function getCache($key, $name)
	{
		$value = $this->readCache($key);
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
	 */
	public function setCache($key, $name, $raw_value)
	{
		$value = $this->readCache($key);
		$value[$name] = $raw_value;

		$this->writeCache($key, $value);
	}
}
