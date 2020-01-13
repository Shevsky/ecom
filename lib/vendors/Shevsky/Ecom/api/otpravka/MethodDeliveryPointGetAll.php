<?php

namespace Shevsky\Ecom\Api\Otpravka;

class MethodDeliveryPointGetAll implements IMethod
{
	public $content = [];

	/**
	 * @return string
	 */
	public function getMethod()
	{
		return EnumMethod::GET;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return '/1.0/delivery-point/findAll';
	}
}
