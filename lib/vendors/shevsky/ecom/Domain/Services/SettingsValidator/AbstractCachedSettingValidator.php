<?php

namespace Shevsky\Ecom\Domain\Services\SettingsValidator;

abstract class AbstractCachedSettingValidator
{
	private $cache_adapter;

	/**
	 * @return \waFileCacheAdapter
	 */
	private function getCacheAdapter()
	{
		if (!isset($this->cache_adapter))
		{
			$this->cache_adapter = new \waFileCacheAdapter(
				[
					'path' => \waConfig::get('wa_path_cache') . '/ecom/setting-validator',
				]
			);
		}

		return $this->cache_adapter;
	}

	/**
	 * @param string $key
	 * @return array
	 */
	protected function readCache($key)
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
	protected function writeCache($key, array $value)
	{
		$this->getCacheAdapter()->set($key, $value);
	}

	/**
	 * @param string $key
	 * @param string $name
	 * @return mixed
	 */
	protected function getCache($key, $name)
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
	 * @param string $raw_value
	 */
	protected function setCache($key, $name, $raw_value)
	{
		$value = $this->readCache($key);
		$value[$name] = $raw_value;

		$this->writeCache($key, $value);
	}
}
