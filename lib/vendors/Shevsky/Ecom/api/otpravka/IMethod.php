<?php

namespace Shevsky\Ecom\Api\Otpravka;

/**
 * @property array $content
 */
interface IMethod
{
	/**
	 * @return string
	 */
	public function getMethod();

	/**
	 * @return string
	 */
	public function getUrl();
}
