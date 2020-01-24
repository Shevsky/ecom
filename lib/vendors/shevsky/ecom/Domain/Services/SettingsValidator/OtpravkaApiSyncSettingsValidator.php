<?php

namespace Shevsky\Ecom\Domain\Services\SettingsValidator;

use LapayGroup\RussianPost\Exceptions\RussianPostException;
use Shevsky\Ecom\Persistence\Services\SettingsValidator\ISettingValidator;
use Shevsky\Ecom\Provider;
use Shevsky\Ecom\Util\KeyValueCacheUtil;

class OtpravkaApiSyncSettingsValidator implements ISettingValidator
{
	const CACHE_UTIL_BASE_KEY = 'settings-validator';

	const CACHE_KEY = 'otpravka_api_sync.cache';
	const CACHE_SAULT = 'otpravka_api_sync_settings_validator';

	private $cache_util;

	public function __construct()
	{
		$this->cache_util = new KeyValueCacheUtil(self::CACHE_UTIL_BASE_KEY);
	}

	/**
	 * @param mixed $settings
	 * @return boolean
	 * @throws \Exception
	 */
	public function validate($settings)
	{
		$cache_name = md5(
			self::CACHE_SAULT . $settings['api_login'] . $settings['api_password'] . $settings['api_token']
			. $settings['index_from']
		);

		$cached_result = $this->cache_util->getCache(self::CACHE_KEY, $cache_name);
		if ($cached_result === true)
		{
			return true;
		}

		try
		{
			$shipping_points = Provider::getOtpravkaApi(
				$settings['api_login'],
				$settings['api_password'],
				$settings['api_token']
			)->shippingPoints();

			$shipping_point_indexes = array_map([__CLASS__, 'getShippingPointIndex'], $shipping_points);

			if (!in_array($settings['index_from'], $shipping_point_indexes))
			{
				$shipping_point_indexes_string = implode(', ', $shipping_point_indexes);

				throw new \Exception(
					"Индекс отправки {$settings['index_from']} недоступен для вашего аккаунта (доступны: {$shipping_point_indexes_string})"
				);
			}
		}
		catch (RussianPostException $e)
		{
			if ($e->getErrorSubCode() === 'UNAUTHORIZED')
			{
				throw new \Exception('Указаны некорректные данные для авторизации API сервиса Отправка Почта России');
			}

			$this->cache_util->setCache(self::CACHE_KEY, $cache_name, false);
		}

		$this->cache_util->setCache(self::CACHE_KEY, $cache_name, true);

		return true;
	}

	/**
	 * @return string
	 */
	private function getShippingPointIndex($shipping_point)
	{
		if (is_array($shipping_point) && array_key_exists('operator-postcode', $shipping_point))
		{
			return $shipping_point['operator-postcode'];
		}

		return '';
	}
}
