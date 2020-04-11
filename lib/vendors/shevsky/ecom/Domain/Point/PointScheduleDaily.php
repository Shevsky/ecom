<?php

namespace Shevsky\Ecom\Domain\Point;

use Shevsky\Ecom\Persistence\Point\IPointScheduleDaily;

class PointScheduleDaily implements IPointScheduleDaily
{
	private $data;
	private $match_data;

	/**
	 * @param array $data
	 */
	public function __construct($data)
	{
		$this->data = $data;

		preg_match('/^([0-9]{2}):([0-9]{2})-([0-9]{2}):([0-9]{2})$/', $data, $this->match_data);
	}

	/**
	 * @return bool
	 */
	public function isWorking()
	{
		return !empty($this->data) && !empty($this->match_data);
	}

	/**
	 * @return string
	 */
	public function getHourFrom()
	{
		return $this->match_data[1];
	}

	/**
	 * @return string
	 */
	public function getMinutesFrom()
	{
		return $this->match_data[2];
	}

	/**
	 * @return string
	 */
	public function getHourTo()
	{
		return $this->match_data[3];
	}

	/**
	 * @return string
	 */
	public function getMinutesTo()
	{
		return $this->match_data[4];
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return [
			'is_working' => $this->isWorking(),
			'hour_from' => $this->getHourFrom(),
			'hour_to' => $this->getHourTo(),
			'minutes_from' => $this->getMinutesFrom(),
			'minutes_to' => $this->getMinutesTo(),
		];
	}
}
