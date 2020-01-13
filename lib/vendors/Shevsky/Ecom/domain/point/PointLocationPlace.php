<?php

namespace Shevsky\Ecom\Domain\Point;

use Shevsky\Ecom\Persistence\Point\IPointLocationPlace;

class PointLocationPlace implements IPointLocationPlace
{
	private $data;

	/**
	 * @param array $data = [
	 *  'region' => string,
	 *  'region_code' => int,
	 *  'place' => string,
	 *  'city_name' => string,
	 *  'micro_district' => string,
	 *  'area' => string,
	 * ]
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}

	/**
	 * @return string
	 */
	public function getRegion()
	{
		return $this->data['region'];
	}

	/**
	 * @return int
	 */
	public function getRegionCode()
	{
		return (int)$this->data['region_code'];
	}

	/**
	 * @return string
	 */
	public function getPlace()
	{
		return $this->data['place'];
	}

	/**
	 * @return string
	 */
	public function getCityName()
	{
		return $this->data['city_name'];
	}

	/**
	 * @return string
	 */
	public function getMicroDistrict()
	{
		return $this->data['micro_district'];
	}

	/**
	 * @return string
	 */
	public function getArea()
	{
		return $this->data['area'];
	}
}
