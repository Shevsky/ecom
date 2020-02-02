<?php

namespace Shevsky\Ecom\Util;

class StaticCacheUtil
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
	 * @param string $key
	 * @return mixed
	 */
	public function readCache($key)
	{
		$value = $this->getCacheAdapter()->get($key);

		return $value;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @param int|null $expiration
	 */
	public function writeCache($key, $value, $expiration = null)
	{
		$this->getCacheAdapter()->set($key, $value, $expiration);
	}

	/**
	 * @return \waFileCacheAdapter
	 */
	protected function getCacheAdapter()
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
}
