<?php

namespace Shevsky\Ecom\Util;

use Shevsky\Ecom\Persistence\Point\IPointSchedule;
use Shevsky\Ecom\Persistence\Point\IPointScheduleDaily;

class PointScheduleHelper
{
	/**
	 * @param IPointSchedule $schedule
	 * @return int
	 */
	public static function getWorkingDaysCount(IPointSchedule $schedule)
	{
		$array_schedule = $schedule->toArray();
		$array_schedule_working_days = array_filter($array_schedule, [__CLASS__, 'predicateScheduleDailyIsWorking']);

		return count($array_schedule_working_days);
	}

	/**
	 * @param IPointScheduleDaily $schedule_daily
	 * @return bool
	 */
	private static function predicateScheduleDailyIsWorking(IPointScheduleDaily $schedule_daily)
	{
		return $schedule_daily->isWorking();
	}
}
