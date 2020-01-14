<?php

namespace Shevsky\Ecom\Domain\Point;

use Shevsky\Ecom\Persistence\Point\IPointLocation;
use Shevsky\Ecom\Persistence\Point\IPointLocationBuilding;
use Shevsky\Ecom\Persistence\Point\IPointLocationPlace;

class PointLocation implements IPointLocation
{
	private $data;
	private $place;
	private $building;

	/**
	 * @param array $data = [
	 *  'latitude' => string,
	 *  'longitute' => string,
	 *  'type' => string,
	 *  'region' => string,
	 *  'region_code' => string,
	 *  'place' => string,
	 *  'city_name' => string,
	 *  'micro_district' => string,
	 *  'area' => string,
	 *  'street' => string,
	 *  'house' => string,
	 *  'building' => string,
	 *  'corpus' => string,
	 *  'letter' => string,
	 *  'hotel' => string,
	 *  'room' => string,
	 *  'slash' => string,
	 *  'office' => string,
	 *  'vladenie' => string,
	 * ]
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}

	/**
	 * @return float
	 */
	public function getLatitude()
	{
		return (float)$this->data['latitude'];
	}

	/**
	 * @return float
	 */
	public function getLongitude()
	{
		return (float)$this->data['longitute'];
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->data['type'];
	}

	/**
	 * @return IPointLocationPlace
	 */
	public function getPlace()
	{
		if (!isset($this->place))
		{
			$this->place = new PointLocationPlace(
				[
					'region' => $this->data['region'],
					'region_code' => $this->data['region_code'],
					'place' => $this->data['place'],
					'city_name' => $this->data['city_name'],
					'micro_district' => $this->data['micro_district'],
					'area' => $this->data['area'],
				]
			);
		}

		return $this->place;
	}

	/**
	 * @return IPointLocationBuilding
	 */
	public function getBuilding()
	{
		if (!isset($this->building))
		{
			$this->building = new PointLocationBuilding(
				[
					'street' => $this->data['street'],
					'house' => $this->data['house'],
					'building' => $this->data['building'],
					'corpus' => $this->data['corpus'],
					'letter' => $this->data['letter'],
					'hotel' => $this->data['hotel'],
					'room' => $this->data['room'],
					'slash' => $this->data['slash'],
					'office' => $this->data['office'],
					'vladenie' => $this->data['vladenie'],
				]
			);
		}

		return $this->building;
	}
}
