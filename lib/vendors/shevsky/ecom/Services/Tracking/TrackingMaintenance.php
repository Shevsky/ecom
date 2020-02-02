<?php

namespace Shevsky\Ecom\Services\Tracking;

use LapayGroup\RussianPost\Providers\Tracking;
use Shevsky\Ecom\Util\StaticCacheUtil;

class TrackingMaintenance
{
	const CACHE_UTIL_BASE_KEY = 'tracking';

	private $tracking;
	private $cache_lifetime;
	private $cache_util;

	/**
	 * @param Tracking $tracking
	 * @param int $cache_lifetime
	 */
	public function __construct(Tracking $tracking, $cache_lifetime = 0)
	{
		$this->tracking = $tracking;
		$this->cache_lifetime = $cache_lifetime;
		$this->cache_util = new StaticCacheUtil(self::CACHE_UTIL_BASE_KEY);
	}

	/**
	 * @param string $tracking_id
	 * @return TrackingHistory
	 * @throws \Exception
	 */
	public function getHistory($tracking_id)
	{
		if (!$this->isCaching())
		{
			return $this->createHistory($tracking_id);
		}

		$raw_history = $this->cache_util->readCache("{$tracking_id}.cache");
		try
		{
			$history = TrackingHistory::restore($raw_history);
		}
		catch (\Exception $e)
		{
			$history = $this->createHistory($tracking_id);

			$expiration = time() + $this->cache_lifetime;
			$this->cache_util->writeCache("{$tracking_id}.cache", $history->memento(), $expiration);
		}

		return $history;
	}

	/**
	 * @return bool
	 */
	private function isCaching()
	{
		return $this->cache_lifetime > 0;
	}

	/**
	 * @param string $tracking_id
	 * @return TrackingHistory
	 * @throws \Exception
	 */
	private function createHistory($tracking_id)
	{
		/**
		 * @var \stdClass[] $raw_history
		 */
		$raw_history = $this->tracking->getOperationsByRpo($tracking_id);
		$records = array_map([TrackingRecord::class, 'buildWithHistoryRecordObject'], $raw_history);
		krsort($records);

		$history = new TrackingHistory($records);

		return $history;
	}
}
