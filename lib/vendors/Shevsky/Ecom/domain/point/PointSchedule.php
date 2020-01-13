<?php

namespace Shevsky\Ecom\Domain\Point;

use Shevsky\Ecom\Persistence\Point\IPointSchedule;
use Shevsky\Ecom\Persistence\Point\IPointScheduleDaily;

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
	 * @return IPointScheduleDaily
	 */
	public function getMonday()
	{
		return $this->data['monday'];
	}

	/**
	 * @return IPointScheduleDaily
	 */
	public function getTuesday()
	{
		return $this->data['tuesday'];
	}

	/**
	 * @return IPointScheduleDaily
	 */
	public function getWednesday()
	{
		return $this->data['wednesday'];
	}

	/**
	 * @return IPointScheduleDaily
	 */
	public function getThursday()
	{
		return $this->data['thursday'];
	}

	/**
	 * @return IPointScheduleDaily
	 */
	public function getFriday()
	{
		return $this->data['friday'];
	}

	/**
	 * @return IPointScheduleDaily
	 */
	public function getSaturday()
	{
		return $this->data['saturday'];
	}

	/**
	 * @return IPointScheduleDaily
	 */
	public function getSunday()
	{
		return $this->data['sunday'];
	}
}
