<?php

namespace Shevsky\Ecom\Domain\Point;

use Shevsky\Ecom\Persistence\Point\IPointSchedule;

class PointSchedule implements IPointSchedule
{
	private $data;

	/**
	 * @param array $data = [
	 *  'monday' => IPointScheduleDaily,
	 *  'tuesday' => IPointScheduleDaily,
	 *  'wednesday' => IPointScheduleDaily,
	 *  'thursday' => IPointScheduleDaily,
	 *  'friday' => IPointScheduleDaily,
	 *  'saturday' => IPointScheduleDaily,
	 *  'sunday' => IPointScheduleDaily,
	 * ]
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}

	/**
	 * @return PointScheduleDaily
	 */
	public function getMonday()
	{
		return $this->data['monday'];
	}

	/**
	 * @return PointScheduleDaily
	 */
	public function getTuesday()
	{
		return $this->data['tuesday'];
	}

	/**
	 * @return PointScheduleDaily
	 */
	public function getWednesday()
	{
		return $this->data['wednesday'];
	}

	/**
	 * @return PointScheduleDaily
	 */
	public function getThursday()
	{
		return $this->data['thursday'];
	}

	/**
	 * @return PointScheduleDaily
	 */
	public function getFriday()
	{
		return $this->data['friday'];
	}

	/**
	 * @return PointScheduleDaily
	 */
	public function getSaturday()
	{
		return $this->data['saturday'];
	}

	/**
	 * @return PointScheduleDaily
	 */
	public function getSunday()
	{
		return $this->data['sunday'];
	}

	/**
	 * @return PointScheduleDaily[]
	 */
	public function toArray()
	{
		return [
			$this->getSunday(),
			$this->getMonday(),
			$this->getTuesday(),
			$this->getWednesday(),
			$this->getThursday(),
			$this->getFriday(),
			$this->getSaturday()
		];
	}
}
