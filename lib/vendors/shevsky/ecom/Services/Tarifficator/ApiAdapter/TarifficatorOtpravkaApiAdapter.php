<?php

namespace Shevsky\Ecom\Services\Tarifficator\ApiAdapter;

use Shevsky\Ecom\Services\OtpravkaApi\OtpravkaApi;
use LapayGroup\RussianPost\Exceptions\RussianPostException;
use LapayGroup\RussianPost\ParcelInfo;
use LapayGroup\RussianPost\TariffInfo;

class TarifficatorOtpravkaApiAdapter implements ITarifficatorApiAdapter
{
	private $otpravka_api;
	private $memento_key;

	/**
	 * @param OtpravkaApi $otpravka_api
	 * @param string $memento_key
	 */
	public function __construct(OtpravkaApi $otpravka_api, $memento_key)
	{
		$this->otpravka_api = $otpravka_api;
		$this->memento_key = $memento_key;
	}

	/**
	 * @param ParcelInfo $parcel_info
	 * @return TariffInfo
	 * @throws RussianPostException
	 */
	public function getTariff(ParcelInfo $parcel_info)
	{
		return $this->otpravka_api->getDeliveryTariff($parcel_info);
	}

	/**
	 * @return string
	 */
	public function getMementoKey()
	{
		return $this->memento_key;
	}
}
