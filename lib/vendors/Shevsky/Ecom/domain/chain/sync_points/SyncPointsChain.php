<?php

namespace Shevsky\Ecom\Chain\SyncPoints;

use LapayGroup\RussianPost\Providers\OtpravkaApi;
use Shevsky\Ecom\Persistence\Chain\AbstractChain;
use Shevsky\Ecom\Persistence\Chain\IResponsibility;

class SyncPointsChain extends AbstractChain
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
	 * @return IResponsibility[]
	 */
	protected function getResponsibilities()
	{
		return [
			new GetPointsResponsibility($this->otpravka_api),
			new SavePointsResponsibility(),
		];
	}
}
