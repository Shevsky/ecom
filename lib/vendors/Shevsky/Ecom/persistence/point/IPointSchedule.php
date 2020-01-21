<?php

namespace Shevsky\Ecom\Persistence\Point;

interface IPointSchedule
{
	/**
	 * @return IPointScheduleDaily
	 */
	public function getMonday();

	/**
	 * @return IPointScheduleDaily
	 */
	public function getTuesday();

	/**
	 * @return IPointScheduleDaily
	 */
	public function getWednesday();

	/**
	 * @return IPointScheduleDaily
	 */
	public function getThursday();

	/**
	 * @return IPointScheduleDaily
	 */
	public function getFriday();

	/**
	 * @return IPointScheduleDaily
	 */
	public function getSaturday();

	/**
	 * @return IPointScheduleDaily
	 */
	public function getSunday();

	/**
	 * @return IPointScheduleDaily[]
	 */
	public function toArray();
}
