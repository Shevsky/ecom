<?php

namespace Shevsky\Ecom\Plugin;

use Shevsky\Ecom\Api\Otpravka\OtpravkaApi;

/**
 * @mixin \ecomShipping
 */
trait Api
{
	/**
	 * @return OtpravkaApi
	 * @throws \Exception
	 */
	protected function getOtpravkaApi()
	{
		if (!$this->hasOtpravkaApiParams())
		{
			throw new \Exception('Параметры для API сервиса Отправка не указаны');
		}

		return new OtpravkaApi($this->api_login, $this->api_password, $this->api_token);
	}

	/**
	 * @return bool
	 */
	protected function hasOtpravkaApiParams()
	{
		return !!$this->api_login && !!$this->api_password && !!$this->api_token;
	}

	/**
	 * @return bool
	 */
	protected function hasTrackingApiParams()
	{
		return !!$this->tracking_login && !!$this->tracking_password;
	}
}
