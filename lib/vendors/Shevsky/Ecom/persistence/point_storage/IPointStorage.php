<?php

namespace Shevsky\Ecom\Persistence\PointStorage;

use Shevsky\Ecom\Persistence\Point\IPoint;

interface IPointStorage
{
	/**
	 * @param string $region_code
	 */
	public function filterByRegionCode($region_code);

	/**
	 * @param string $city_name
	 */
	public function filterByCityName($city_name);

	/**
	 * @return IPoint[]
	 */
	public function receive();

	/**
	 * @return number
	 */
	public function count();
}
