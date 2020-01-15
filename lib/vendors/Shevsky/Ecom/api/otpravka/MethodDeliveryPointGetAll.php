<?php

namespace Shevsky\Ecom\Api\Otpravka;

use Shevsky\Ecom\Enum;

class MethodDeliveryPointGetAll implements IMethod
{
	public $content = [];

	/**
	 * @return string
	 */
	public function getMethod()
	{
		return Enum\RequestMethod::GET;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return '/1.0/delivery-point/findAll';
	}
}
