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
	public function getWay();

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

	/**
	 * @return string
	 */
	public function getAddress();

	/**
	 * @return string
	 */
	public function getFullAddress();
}
