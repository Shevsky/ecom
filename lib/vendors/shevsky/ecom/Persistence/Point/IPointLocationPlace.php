<?php

namespace Shevsky\Ecom\Persistence\Point;

interface IPointLocationPlace
{
	/**
	 * @return string
	 */
	public function getRegion();

	/**
	 * @return int
	 */
	public function getRegionCode();

	/**
	 * @return string
	 */
	public function getPlace();

	/**
	 * @return string
	 */
	public function getCityName();

	/**
	 * @return string
	 */
	public function getMicroDistrict();

	/**
	 * @return string
	 */
	public function getArea();
}
