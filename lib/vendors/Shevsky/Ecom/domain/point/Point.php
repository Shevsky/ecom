<?php

namespace Shevsky\Ecom\Domain\Point;

use Shevsky\Ecom\Persistence\Point\IPoint;
use Shevsky\Ecom\Persistence\Point\IPointLocation;
use Shevsky\Ecom\Persistence\Point\IPointSchedule;

class Point implements IPoint
{
	private $data;
	private $schedule;
	private $location;

	/**
	 * @param array $data = [
	 *  'id' => number,
	 *  'object_id' => number,
	 *  'name' => string,
	 *  'description' => string,
	 *  'legal_name' => string,
	 *  'legal_short_name' => string,
	 *  'status' => int,
	 *  'type' => string,
	 *  'office_index' => string,
	 *  'latitude' => string,
	 *  'longitude' => string,
	 *  'location_type' => string,
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
	 *  'options_json' => string,
	 *  'schedule_monday' => string,
	 *  'schedule_tuesday' => string,
	 *  'schedule_wednesday' => string,
	 *  'schedule_thursday' => string,
	 *  'schedule_friday' => string,
	 *  'schedule_saturday' => string,
	 *  'schedule_sunday' => string,
	 *  ]
	 * ]
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->data['object_id'];
	}

	/**
	 * @return int
	 */
	public function getIndex()
	{
		return (int)$this->data['office_index'];
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->data['type'];
	}

	/**
	 * @return bool
	 */
	public function getStatus()
	{
		return (bool)$this->data['status'];
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->data['name'];
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->data['description'];
	}

	/**
	 * @return string
	 */
	public function getLegalName()
	{
		return $this->data['legal_name'];
	}

	/**
	 * @return string
	 */
	public function getLegalShortName()
	{
		return $this->data['legal_short_name'];
	}

	/**
	 * @return IPointSchedule
	 */
	public function getSchedule()
	{
		if (!isset($this->schedule))
		{
			$this->schedule = new PointSchedule(
				[
					'monday' => new PointScheduleDaily($this->data['schedule_monday']),
					'tuesday' => new PointScheduleDaily($this->data['schedule_tuesday']),
					'wednesday' => new PointScheduleDaily($this->data['schedule_wednesday']),
					'thursday' => new PointScheduleDaily($this->data['schedule_thursday']),
					'friday' => new PointScheduleDaily($this->data['schedule_friday']),
					'saturday' => new PointScheduleDaily($this->data['schedule_saturday']),
					'sunday' => new PointScheduleDaily($this->data['schedule_sunday']),
				]
			);
		}

		return $this->schedule;
	}

	/**
	 * @return IPointLocation
	 */
	public function getLocation()
	{
		if (!isset($this->location))
		{
			$this->location = new PointLocation(
				[
					'latitude' => $this->data['latitude'],
					'longitute' => $this->data['longitude'],
					'type' => $this->data['location_type'],
					'region' => $this->data['region'],
					'region_code' => $this->data['region_code'],
					'place' => $this->data['place'],
					'city_name' => $this->data['city_name'],
					'micro_district' => $this->data['micro_district'],
					'area' => $this->data['area'],
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

		return $this->location;
	}

	/**
	 * @return string[]
	 */
	public function getOptions()
	{
		$options = json_decode($this->data['options_json'], true);
		if ($options === null || json_last_error() !== JSON_ERROR_NONE)
		{
			return [];
		}

		return $options;
	}
}
