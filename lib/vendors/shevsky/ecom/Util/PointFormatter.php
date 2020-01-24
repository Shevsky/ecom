<?php

namespace Shevsky\Ecom\Util;

class PointFormatter
{
	/**
	 * @param array $point = [
	 *  'address' => [
	 *      'address-type' => string,
	 *      'area' => string,
	 *      'building' => string,
	 *      'corpus' => string,
	 *      'hotel' => string,
	 *      'house' => string,
	 *      'index' => string,
	 *      'letter' => string,
	 *      'location' => string,
	 *      'num-address-type' => string,
	 *      'place' => string,
	 *      'region' => string,
	 *      'room' => string,
	 *      'slash' => string,
	 *      'street' => string,
	 *      'office' => string,
	 *      'vladenie' => string
	 *  ],
	 *  'brand-name' => string,
	 *  'closed' => boolean,
	 *  'delivery-point-index' => string,
	 *  'delivery-point-type' => string,
	 *  'getto' => string,
	 *  'id' => string,
	 *  'legal-name' => string,
	 *  'legal-short-name' => string,
	 *  'temporary-closed' => boolean,
	 *  'work-time' => string[],
	 *  'latitude' => string,
	 *  'longitude' => string,
	 *  'functionality-checking' => string,
	 *  'contents-checking' => string,
	 *  'with-fitting' => string,
	 *  'default_weight-limit' => float,
	 *  'card-payment' => int,
	 *  'cash-payment' => int,
	 *  ]
	 * ]
	 * @return array
	 * @throws \Exception
	 */
	public static function getPointDbDataFromApiData(array $point)
	{
		if (!is_array($point) || !array_key_exists('address', $point) || !array_key_exists('id', $point))
		{
			throw new \Exception('Данные о пункте выдачи некорректны');
		}

		$region_code = RegionFormatter::getRegionCode($point['address']['region']);
		$city_name = RegionFormatter::getCityName($point['address']['place']);

		$schedule = [];
		if (!empty($point['work-time']) && is_array($point['work-time']))
		{
			foreach ($point['work-time'] as $raw_schedule)
			{
				try
				{
					list($day, $schedule_daily) = ScheduleFormatter::parseSchedule($raw_schedule);

					$schedule[$day] = $schedule_daily;
				}
				catch (\Exception $e)
				{
				}
			}
		}

		$options = [];
		if (!empty($point['functionality-checking']))
		{
			$options[] = 'functionality_checking';
		}
		if (!empty($point['contents-checking']))
		{
			$options[] = 'contents_checking';
		}
		if (!empty($point['with-fitting']))
		{
			$options[] = 'with_fitting';
		}
		if (!empty($point['partial-redemption']))
		{
			$options[] = 'partial_redemption';
		}

		$ret = [
			'object_id' => $point['id'],
			'name' => ifset($point, 'brand-name', ''),
			'description' => '',
			'way' => ifset($point, 'getto', ''),
			'legal_name' => ifset($point, 'legal-name', ''),
			'legal_short_name' => ifset($point, 'legal-short-name', ''),
			'status' => !empty(ifset($point, 'closed', false)) ? 0 : 1,
			'weight_limit' => (float)ifset($point, 'default_weight-limit', null),
			'type' => ifset($point, 'delivery-point-type', ''),
			'office_index' => ifset($point, 'delivery-point-index', ''),
			'latitude' => ifset($point, 'latitude', ''),
			'longitude' => ifset($point, 'longitude', ''),
			'location_type' => ifset($point['address'], 'address-type', ''),
			'region' => ifset($point['address'], 'region', ''),
			'region_code' => $region_code,
			'place' => ifset($point['address'], 'place', ''),
			'city_name' => $city_name,
			'micro_district' => ifset($point['address'], 'location', ''),
			'area' => ifset($point['address'], 'area', ''),
			'street' => ifset($point['address'], 'street', ''),
			'house' => ifset($point['address'], 'house', ''),
			'building' => ifset($point['address'], 'building', ''),
			'corpus' => ifset($point['address'], 'corpus', ''),
			'letter' => ifset($point['address'], 'letter', ''),
			'hotel' => ifset($point['address'], 'hotel', ''),
			'room' => ifset($point['address'], 'room', ''),
			'slash' => ifset($point['address'], 'slash', ''),
			'office' => ifset($point['address'], 'office', ''),
			'vladenie' => ifset($point['address'], 'vladenie', ''),
			'card_payment' => !empty(ifset($point, 'card-payment', false)) ? 1 : 0,
			'cash_payment' => !empty(ifset($point, 'cash-payment', false)) ? 1 : 0,
			'schedule_monday' => ifset($schedule, ScheduleFormatter::DAY_MONDAY, null),
			'schedule_tuesday' => ifset($schedule, ScheduleFormatter::DAY_TUESDAY, null),
			'schedule_wednesday' => ifset($schedule, ScheduleFormatter::DAY_WEDNESDAY, null),
			'schedule_thursday' => ifset($schedule, ScheduleFormatter::DAY_THURSDAY, null),
			'schedule_friday' => ifset($schedule, ScheduleFormatter::DAY_FRIDAY, null),
			'schedule_saturday' => ifset($schedule, ScheduleFormatter::DAY_SATURDAY, null),
			'schedule_sunday' => ifset($schedule, ScheduleFormatter::DAY_SUNDAY, null),
			'options_json' => json_encode($options),
		];

		return $ret;
	}
}
