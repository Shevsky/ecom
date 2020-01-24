<?php

namespace Shevsky\Ecom\Plugin;

/**
 * @mixin \ecomShipping
 */
trait Address
{
	/**
	 * @return string
	 */
	protected function getIndex()
	{
		$request_details = \waRequest::request('details');

		if (isset($request_details['shipping_address']['zip']))
		{
			return $request_details['shipping_address']['zip'];
		}

		return $this->getAddress('zip');
	}

	/**
	 * @return string
	 */
	protected function getCountryCode()
	{
		return $this->getAddress('country');
	}

	/**
	 * @return string
	 */
	protected function getRegionCode()
	{
		return $this->getAddress('region');
	}

	/**
	 * @return string
	 */
	protected function getCityName()
	{
		return $this->getAddress('city');
	}
}
