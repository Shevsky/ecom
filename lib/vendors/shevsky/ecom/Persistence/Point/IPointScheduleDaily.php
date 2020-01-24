<?php

namespace Shevsky\Ecom\Persistence\Point;

interface IPointScheduleDaily
{
	/**
	 * @return bool
	 */
	public function isWorking();

	/**
	 * @return string
	 */
	public function getHourFrom();

	/**
	 * @return string
	 */
	public function getMinutesFrom();

	/**
	 * @return string
	 */
	public function getHourTo();

	/**
	 * @return string
	 */
	public function getMinutesTo();
}
