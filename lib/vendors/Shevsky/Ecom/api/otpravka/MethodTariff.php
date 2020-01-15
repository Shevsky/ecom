<?php

namespace Shevsky\Ecom\Api\Otpravka;

use Shevsky\Ecom\Enum;

class MethodTariff implements IMethod
{
	public $content;

	/**
	 * @param array $content = [
	 *  'delivery-point-index' => string,
	 *  'dimension-type' => Enum\DimensionType,
	 *  'index-from' => string,
	 *  'goods-value' => int,
	 *  'index-to' => string,
	 * ]
	 */
	public function __construct(array $content)
	{
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function getMethod()
	{
		return Enum\RequestMethod::POST;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return '/1.0/tariff';
	}
}
