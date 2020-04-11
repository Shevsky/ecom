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
	 *  'way' => string,
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
	public function getWay()
	{
		return $this->data['way'];
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

	/**
	 * @return string
	 */
	public function getAddress()
	{
		$address_parts = [];

		if ($this->getBuilding()->getStreet())
		{
			$address_parts[] = $this->data['street'];
		}

		if ($this->getBuilding()->getHouse())
		{
			$address_part = "д. {$this->getBuilding()->getHouse()}";

			if ($this->getBuilding()->getLetter())
			{
				$address_part .= $this->getBuilding()->getLetter();
			}

			if ($this->getBuilding()->getSlash())
			{
				$address_part .= "/{$this->getBuilding()->getSlash()}";
			}

			if ($this->getBuilding()->getCorpus())
			{
				$address_part .= " корпус {$this->getBuilding()->getCorpus()}";
			}

			if ($this->getBuilding()->getBuilding())
			{
				$address_part .= " строение {$this->getBuilding()->getBuilding()}";
			}

			if ($this->getBuilding()->getVladenie())
			{
				$address_part .= " владение {$this->getBuilding()->getVladenie()}";
			}

			$address_parts[] = $address_part;
		}

		if ($this->getBuilding()->getHotel())
		{
			$address_parts[] = $this->getBuilding()->getHotel();
		}

		if ($this->getBuilding()->getOffice())
		{
			$address_parts[] = "оф. {$this->getBuilding()->getOffice()}";
		}

		if ($this->getBuilding()->getRoom())
		{
			$address_parts[] = $this->getBuilding()->getRoom();
		}

		return implode(', ', $address_parts);
	}

	/**
	 * @return string
	 */
	public function getFullAddress()
	{
		$full_address_parts = [$this->getAddress()];

		if ($this->getPlace()->getRegion())
		{
			$full_address_parts[] = $this->getPlace()->getRegion();
		}

		if ($this->getPlace()->getMicroDistrict())
		{
			$full_address_parts[] = $this->getPlace()->getMicroDistrict();
		}

		if ($this->getPlace()->getCityName())
		{
			$full_address_parts[] = $this->getPlace()->getCityName();
		}

		return implode(', ', $full_address_parts);
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return [
			'latitude' => $this->getLatitude(),
			'longitude' => $this->getLongitude(),
			'way' => $this->getWay(),
			'type' => $this->getType(),
			'address' => $this->getAddress(),
			'full_address' => $this->getFullAddress(),
		];
	}
}
