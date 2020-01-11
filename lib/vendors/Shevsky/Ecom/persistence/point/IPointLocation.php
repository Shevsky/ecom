<?php

namespace Shevsky\Ecom\Persistence\Point;

interface IPointLocation
{
	/**
	 * @return float
	 */
	public function getLatitude();

	/**
	 * @return float
	 */
	public function getLongitude();

	/**
	 * @return string
	 */
	public function getType();

	/**
	 * @return IPointLocationPlace
	 */
	public function getPlace();

	/**
	 * @return IPointLocationBuilding
	 */
	public function getBuilding();
}
