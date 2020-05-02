<?php

namespace Shevsky\Ecom\Chain\SyncPoints;

use Shevsky\Ecom\Services\OtpravkaApi\OtpravkaApi;
use Shevsky\Ecom\Persistence\Chain\IResponsibility;

class GetPointsResponsibility implements IResponsibility
{
	private $otpravka_api;

	public function __construct(OtpravkaApi $otpravka_api)
	{
		$this->otpravka_api = $otpravka_api;
	}

	/**
	 * @param mixed $args
	 * @return mixed[]
	 * @throws \Exception
	 */
	public function execute(array ...$args)
	{
		$points = $this->otpravka_api->getPvzList();

		return [$points];
	}
}
