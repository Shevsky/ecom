<?php

namespace Shevsky\Ecom\Services\Tarifficator\ApiAdapter;

use LapayGroup\RussianPost\Exceptions\RussianPostException;
use LapayGroup\RussianPost\ParcelInfo;
use LapayGroup\RussianPost\TariffInfo;

interface ITarifficatorApiAdapter
{
	/**
	 * @param ParcelInfo $parcel_info
	 * @return TariffInfo
	 * @throws RussianPostException
	 * @throws \Exception
	 */
	public function getTariff(ParcelInfo $parcel_info);

	/**
	 * @return string
	 */
	public function getMementoKey();
}
