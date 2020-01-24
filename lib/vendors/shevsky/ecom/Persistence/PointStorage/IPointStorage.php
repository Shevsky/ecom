<?php

namespace Shevsky\Ecom\Persistence\PointStorage;

use Shevsky\Ecom\Persistence\Point\IPoint;

interface IPointStorage
{
	/**
	 * @param string $region_code
	 * @return self
	 */
	public function filterByRegionCode($region_code);

	/**
	 * @param string $city_name
	 * @return self
	 */
	public function filterByCityName($city_name);

	/**
	 * @param bool $availability
	 * @return self
	 */
	public function filterByCardPaymentAvailability($availability);

	/**
	 * @param bool $availability
	 * @return self
	 */
	public function filterByCashPaymentAvailability($availability);

	/**
	 * @return IPoint[]
	 */
	public function receive();

	/**
	 * @return number
	 */
	public function count();
}
