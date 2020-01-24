<?php

namespace Shevsky\Ecom\Services\Tarifficator\ApiAdapter;

use LapayGroup\RussianPost\Exceptions\RussianPostException;
use LapayGroup\RussianPost\ParcelInfo;
use LapayGroup\RussianPost\Providers\OtpravkaApi;
use LapayGroup\RussianPost\TariffInfo;

class TarifficatorOtpravkaApiAdapter implements ITarifficatorApiAdapter
{
	private $otpravka_api;

	/**
	 * @param OtpravkaApi $otpravka_api
	 */
	public function __construct(OtpravkaApi $otpravka_api)
	{
		$this->otpravka_api = $otpravka_api;
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
}
