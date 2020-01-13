<?php

namespace Shevsky\Ecom\Util;

class ScheduleFormatter
{
	const DAY_MONDAY = 'monday';
	const DAY_TUESDAY = 'tuesday';
	const DAY_WEDNESDAY = 'wednesday';
	const DAY_THURSDAY = 'thursday';
	const DAY_FRIDAY = 'friday';
	const DAY_SATURDAY = 'saturday';
	const DAY_SUNDAY = 'sunday';

	const DAY_LOCALES = [
		'пн' => self::DAY_MONDAY,
		'вт' => self::DAY_TUESDAY,
		'ср' => self::DAY_WEDNESDAY,
		'чт' => self::DAY_THURSDAY,
		'пт' => self::DAY_FRIDAY,
		'сб' => self::DAY_SATURDAY,
		'вс' => self::DAY_SUNDAY,
	];

	/**
	 * @param string $raw_schedule
	 * @return string[]
	 * @throws \Exception
	 */
	public static function parseSchedule($raw_schedule)
	{
		$day_locales_string = implode('|', self::DAY_LOCALES);

		if (preg_match(
			'/(' . $day_locales_string . ').*?([0-9]{1,2}):([0-9]{1,2}).*?([0-9]{1,2}):([0-9]{1,2})/iu',
			$raw_schedule,
			$matches
		))
		{
			$day = self::DAY_LOCALES[$matches[1]];

			$hour_from = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
			$minutes_from = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
			$hour_to = str_pad($matches[4], 2, '0', STR_PAD_LEFT);
			$minutes_to = str_pad($matches[5], 2, '0', STR_PAD_LEFT);

			$schedule_daily = "{$hour_from}:{$minutes_from}-{$hour_to}:{$minutes_to}";

			return [
				$day,
				$schedule_daily,
			];
		}

		throw new \Exception('Не удалось распознать режим работы');
	}
}
