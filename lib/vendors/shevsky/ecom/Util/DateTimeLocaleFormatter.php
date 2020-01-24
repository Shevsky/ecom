<?php

namespace Shevsky\Ecom\Util;

class DateTimeLocaleFormatter
{
	private static $month_locale_pool = [
		'',
		'января',
		'февраля',
		'марта',
		'апреля',
		'мая',
		'июня',
		'июля',
		'августа',
		'сентября',
		'октября',
		'ноября',
		'декабря',
	];

	/**
	 * @param \DateTime $datetime
	 * @param bool $with_year
	 * @param bool $with_month
	 * @return string
	 */
	public static function format(\DateTime $datetime, $with_year = true, $with_month = true)
	{
		$date = $datetime->format('j');

		if (!$with_month)
		{
			return $date;
		}

		$date .= ' ' . self::$month_locale_pool[$datetime->format('n')];

		if (!$with_year)
		{
			return $date;
		}

		$date .= ' ' . $datetime->format('Y');

		return $date;
	}

	/**
	 * @param \DateTime[] $datetime_interval
	 * @param bool $with_year
	 * @return string
	 */
	public static function formatInterval(array $datetime_interval, $with_year = true)
	{
		if (empty($datetime_interval))
		{
			return '';
		}

		if (count($datetime_interval) === 1)
		{
			return self::format($datetime_interval[0], $with_year);
		}

		list($from_datetime, $to_datetime) = $datetime_interval;

		$is_same_year = $from_datetime->format('Y') === $to_datetime->format('Y');
		$is_same_month = $is_same_year && $from_datetime->format('n') === $to_datetime->format('n');

		$to_date = self::format($to_datetime, $is_same_year ? $with_year : true);

		if ($is_same_month)
		{
			$from_date = self::format($from_datetime, false, false);
		}
		else if ($is_same_year)
		{
			$from_date = self::format($from_datetime, false);
		}
		else
		{
			$from_date = self::format($from_datetime);
		}

		return "от {$from_date} до {$to_date}";
	}
}
