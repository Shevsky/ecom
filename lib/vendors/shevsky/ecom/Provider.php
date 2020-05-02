<?php

namespace Shevsky\Ecom;

use LapayGroup\RussianPost\Providers\Tracking;
use Shevsky\Ecom\Services\OtpravkaApi\OtpravkaApi;

class Provider
{
	/**
	 * @param string $login
	 * @param string $password
	 * @param string $token
	 * @return OtpravkaApi
	 * @throws \Exception
	 */
	public static function getOtpravkaApi($login, $password, $token)
	{
		if (!$login || !$password || !$token)
		{
			throw new \Exception('Параметры для API сервиса Отправка не указаны');
		}

		$key = base64_encode($login . ':' . $password);

		return new OtpravkaApi(
			[
				'auth' => [
					'otpravka' => [
						'token' => $token,
						'key' => $key,
					],
				],
			]
		);
	}

	/**
	 * @param string $login
	 * @param string $password
	 * @return Tracking
	 * @throws \SoapFault
	 * @throws \Exception
	 */
	public static function getTracking($login, $password)
	{
		if (!class_exists('SoapClient'))
		{
			throw new \Exception('Трекинг недоступен: расширение SOAP не установлено');
		}

		if (!$login || !$password)
		{
			throw new \Exception('Параметры для API Трекинга не указаны');
		}

		return new Tracking(
			'single',
			[
				'auth' => [
					'tracking' => [
						'login' => $login,
						'password' => $password,
					],
				],
			]
		);
	}
}
